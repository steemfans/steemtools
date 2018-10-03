<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Cache\Simple\FilesystemCache;

class UserController extends Controller
{
    /**
     * @Route("/user/login", name="user_login")
     */
    public function login(Request $request, \Swift_Mailer $mailer, SessionInterface $session)
    {
        if ($_POST) {
            $email = $request->request->get('email', null);
            if (!$email) {
                $this->addFlash(
                    'danger',
                    'Email error.'
                );
                return $this->redirectToRoute('homepage');
            }
            $em = $this->getDoctrine()->getManager();

            // generate an authcode
            $authcode = random_int(10000000, 99999999);

            // find user by email
            $user = $em->getRepository(User::class)->findOneBy([
                'email' => $email,
            ]);

            // create new user
            if (!$user) {
                $user = new User();
                $user->setEmail($email);
            }

            $user->setAuthcode($authcode);
            $user->setExpiredAt(time() + 10 * 60);
            $em->persist($user);
            $em->flush();

            // save email to session
            $session->set('current_email', $email);

            // check send frequently?
            $cache = new FilesystemCache();
            $send_time = $cache->has('send_time_'.md5($email)) ? $cache->get('send_time_'.md5($email)) : 0;
            if ($send_time >= 10) {
                $this->addFlash(
                    'danger',
                    'Send frequently.'
                );
                return $this->redirectToRoute('homepage');
            }
            $cache->set('send_time_'.md5($email), $send_time++);

            $sys_email = getenv('SYS_EMAIL');
            // send email
            $message = (new \Swift_Message('Your authcode from Steem Mention!'))
                ->setFrom($sys_email)
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'emails/authcode.html.twig',
                        array('authcode' => $authcode)
                    ),
                    'text/html'
                );
            $mailer->send($message);

            // jump to check page
            return $this->redirectToRoute('user_check');
        }
        return $this->render('user/login.html.twig', array(
            'title' => 'Steem Mention',
            'login_status' => $session->get('login_status'),
        ));
    }

    /**
     * @Route("/user/check", name="user_check")
     */
    public function check(Request $request, \Swift_Mailer $mailer, SessionInterface $session)
    {
        $email = $session->get('current_email', null);
        
        if (!$email) {
            $this->addFlash(
                'danger',
                'Email error.'
            );
            return $this->redirectToRoute('user_login');
        }

        $em = $this->getDoctrine()->getManager();

        // find user by email
        $user = $em->getRepository(User::class)->findOneBy([
            'email' => $email,
        ]);

        if (!$user) {
            $this->addFlash(
                'danger',
                'User not exist.'
            );
            return $this->redirectToRoute('user_login');
        }

        if ($_POST) {
            $authcode = $request->request->get('authcode', null);

            // check expired?
            if ($user->getExpiredAt() < time()) {
                $this->addFlash(
                    'danger',
                    'Authcode expired.'
                );
                return $this->redirectToRoute('user_login');
            }

            // check authcode
            if ($user->getAuthcode() != $authcode) {
                $this->addFlash(
                    'danger',
                    'Authcode error.'
                );
                return $this->redirectToRoute('user_login');
            }

            // login success and empty cache
            $cache = new FilesystemCache();
            $cache->delete('send_time_'.md5($email));
            
            // save login status into session
            $session->set('login_status', true);

            // redirect
            return $this->redirectToRoute('user_setting');
        }
        
        return $this->render('user/check.html.twig', array(
            'title' => 'Steem Mention',
            'login_status' => $session->get('login_status'),
            'email' => $email,
        ));
    }

    /**
     * @Route("/user/setting", name="user_setting")
     */
    public function setting(Request $request, SessionInterface $session)
    {
        if ($session->get('login_status') != true) {
            $this->addFlash(
                'danger',
                'Need login first.'
            );
            return $this->redirectToRoute('user_login');
        }

        // base setting [const]
        $base_settings = [
            'replies' => 'off',
            'transfer' => 'off',
        ];

        $email = $session->get('current_email', null);

        $em = $this->getDoctrine()->getManager();
        // find user by email
        $user = $em->getRepository(User::class)->findOneBy([
            'email' => $email,
        ]);

        // init setting
        $settings = [];
        $saved_setting = json_decode($user->getSetting());
        if ($saved_setting) {
            foreach ($saved_setting as $k => $v) {
                if (isset($base_settings[$k])) {
                    $settings[$k] = $v;
                } else {
                    $settings[$k] = 'off';
                }
            }
        } else {
            $settings = $base_settings;
        }

        if ($_POST) {
            $username = $request->request->get('username', null);
            $post_settings = $request->request->get('settings', []);

            if ($post_settings) {
                foreach ($base_settings as $k => $v) {
                    if (isset($post_settings[$k])) {
                        $settings[$k] = $v;
                    } else {
                        $settings[$k] = 'off';
                    }
                }
            } else {
                $settings = $base_settings;
            }

            $user->setUsername($username);
            $user->setSetting(json_encode($settings));
            $em->persist($user);

            $em->flush();

            $this->addFlash(
                'success',
                'Update success.'
            );
        }

        return $this->render('user/setting.html.twig', array(
            'title' => 'Steem Mention',
            'login_status' => $session->get('login_status'),
            'user' => $user,
            'settings' => $settings,
        ));
    }

    /**
     * @Route("/user/logout", name="user_logout")
     */
    public function logout(SessionInterface $session)
    {
        $session->set('login_status', false);
        $session->set('current_email', null);
        $this->addFlash(
            'success',
            'Logout success.'
        );
        return $this->redirectToRoute('homepage');
    }
}
