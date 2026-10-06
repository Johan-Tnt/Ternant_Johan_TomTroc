<?php

namespace App\Controller;

use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Service\View;

class MessageController
{
    //Affiche la messagerie de l'utilisateur connecté 
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit;
        }

        $userId = (int) $_SESSION['user_id'];

        $conversationRepository = new ConversationRepository();
        $messageRepository = new MessageRepository();

        $conversations = $conversationRepository->findByUserIdWithDetails($userId);

        $conversation = null;
        $messages = [];

        $conversationId = (int) ($_GET['id'] ?? 0);

       if ($conversationId > 0) {
            $conversation = $conversationRepository->findByIdForUser(
                $conversationId,
                $userId
            );

            if ($conversation !== null) {

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                    $content = trim($_POST['content'] ?? '');

                    if ($content !== '') {
                        $messageRepository->createMessage(
                            $conversationId,
                            $userId,
                            $content
                        );
                    }

                    header(
                        'Location: index.php?route=messaging&id=' . $conversationId
                    );

                    exit;
                }

                $messages = $messageRepository->findByConversationId(
                    $conversationId,
                );

                //Marque comme lus les messages reçus dans une conversation
                if (!empty($messages)) {
                    $lastMessageId = end($messages)->getId();

                    $messageRepository->markMessagesAsRead(
                        $conversationId,
                        $userId,
                        $lastMessageId
                    );
                }
            }
        }

        View::getInstance()->render(
            'messaging',
            'Messagerie',
            [
                'conversations' => $conversations,
                'conversation' => $conversation,
                'messages' => $messages
            ]
        );
    }

    //Rafraîchit les messages d'une conversation 
    public function refresh(): void 
    {
        if(!isset($_SESSION ['user_id'])) {
            http_response_code(401);
            return;
        }

        $userId = (int) $_SESSION['user_id'];

        $conversationId = (int) ($_GET['id'] ?? 0);

        if ($conversationId <=0) {
            http_response_code(400);
            return;
        }

        $conversationRepository = new ConversationRepository();
         
        $conversation = $conversationRepository->findByIdForUser(
            $conversationId,
            $userId
        );

        if ($conversation === null) {
            http_response_code(403);
            return;
        }

        $lastMessageId = (int) ($_GET['last_message_id'] ?? 0);

        $messageRepository = new MessageRepository();

        $messages = $messageRepository->findByConversationId(
            $conversationId,
            $lastMessageId
        );

        if (!empty($messages)) {
            $newLastMessageId = end($messages)->getId();
            
            $messageRepository->markMessagesAsRead(
                $conversationId,
                $userId,
                $newLastMessageId
            );
        }

        $showEmptyMessage = false;

        require __DIR__ .'/../View/Partials/messages.php';
    }

    //Récupère le nombre de messages non lus 
    public function countUnread(): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            return;
        }

        $userId = (int) $_SESSION['user_id'];

        $messageRepository = new MessageRepository();

        $unreadMessageCount = $messageRepository->countUnreadMessages(
            $userId
        );

        header('Content-Type: application/json');

        echo json_encode([
            'count' => $unreadMessageCount
        ]);
    }

    //Ouvre une conversation avec un utilisateur 
    public function startConversation(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?route=login');
            exit;
        }

        $userId = (int) $_SESSION['user_id'];
        $otherUserId = (int) ($_GET['user_id'] ?? 0);

        //Empêche de créer une conversation avec soi-même
        if ($otherUserId <= 0 || $otherUserId === $userId) {
            header('Location: index.php?route=books');
            exit;
        }

        $conversationRepository = new ConversationRepository();

        //Recherche une conversation existante 
        $conversation = $conversationRepository->findBetweenUsers(
            $userId,
            $otherUserId
        );

        //Crée la conversation si elle n'existe pas 
        if ($conversation === null) {
            $conversation = $conversationRepository->createConversation(
                $userId,
                $otherUserId
            );

        }

        header(
            'Location: index.php?route=messaging&id=' . $conversation->getId()
        );

        exit;
    }
}