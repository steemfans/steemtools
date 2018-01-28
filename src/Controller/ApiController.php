<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use App\Entity\User;

class ApiController extends Controller
{
    /**
     * @Route("/api/block/watcher", name="block_watcher")
     */
    public function blockWatcher(Request $request, LoggerInterface $logger, \Swift_Mailer $mailer)
    {
        $data = json_decode($request->getContent(), true);
        // $logger->debug(var_export( $data, true ));

        $parent_author = isset($data['parent_author']) ? $data['parent_author'] : null;
        $parent_permlink = isset($data['parent_permlink']) ? $data['parent_permlink'] : null;
        $author = isset($data['author']) ? $data['author'] : null;
        $permlink = isset($data['permlink']) ? $data['permlink'] : null;
        $title = isset($data['title']) ? $data['title'] : null;
        $body = isset($data['body']) ? $data['body'] : null;
        $json_metadata = isset($data['json_metadata']) ? $data['json_metadata'] : null;

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy([
            'username' => $parent_author,
        ]);

        if ($user) {
            $settings = json_decode( $user->getSetting() );
            if ( isset($settings['replies']) && $settings['replies'] == 'on') {
                $email = $user->getEmail();
                $reply_url = 'https://steemit.com/'.$parent_permlink.'/@'.$author.'/'.$permlink;
                
                $sys_email = getenv('SYS_EMAIL');
                // send email
                $message = (new \Swift_Message('[SteemMention] You have a NEW reply!'))
                    ->setFrom($sys_email)
                    ->setTo($email)
                    ->setBody(
                        $this->renderView(
                            'emails/reply.html.twig',
                            [
                                'reply_url' => $reply_url,
                                'author' => $author,
                                'parent_author' => $parent_author,
                            ]
                        ),
                        'text/html'
                    );
                $mailer->send($message);
                $result = 'get ' . $parent_author . ' and sent an email to ' . $email;
                $code = 1;
            } else {
                $result = 'get ' . $parent_author;
                $code = 0;
            }
        } else {
            $result = $parent_author . ' is not in db.';
            $code = -1;
        }

        return $this->json([
            'result' => $result,
            'code' => $code,
        ]);
    }
}
