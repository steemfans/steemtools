<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class ApiController extends Controller
{
    /**
     * @Route("/api/block/watcher", name="block_watcher")
     */
    public function blockWatcher(Request $request, LoggerInterface $logger)
    {
        $data = $request->request->
        $logger->info('I just got the logger');
    }
}
