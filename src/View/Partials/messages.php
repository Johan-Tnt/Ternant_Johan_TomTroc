<?php if (empty($messages)) : ?>

    <?php if (($showEmptyMessage ?? true)) : ?>

        <p class="messaging_empty">
            Aucun message dans cette conversation.
        </p>

    
    <?php endif; ?>

<?php else : ?>

    <?php foreach ($messages as $message) : ?>

        <?php
        //Détermine si le message a été envoyé par l'utilisateur connecté
        $isSent = $message->getSenderId() === (int) $_SESSION['user_id'];
        ?>

        <div 
            class="messaging_message_wrapper <?= $isSent ? 'messaging_message_wrapper--sent' : 'messaging_message_wrapper--received' ?>"
            data-message-id="<?= $message->getId() ?>"
        >

            <?php if (!$isSent) : ?>

                <div class="messaging_message_sender">

                    <div class="messaging_message_header">

                       <div class="messaging_avatar">
                            <img 
                                src="assets/images/avatars/<?= htmlspecialchars(
                                    $message->getSenderAvatar()
                                ) ?>" 
                                alt=""
                            >
                        </div>

                        <span class="messaging_time">
                            <?= date(
                                'd/m/Y à H:i',
                                strtotime($message->getCreatedAt())
                            ) ?>
                        </span>

                    </div>

                    <div class="messaging_message messaging_message--received">

                        <p>
                            <?= htmlspecialchars(
                               $message->getContent()
                            ) ?>
                        </p>

                    </div>

                </div>

            <?php else : ?>

                <span class="messaging_time">
                    <?= date(
                        'd/m/Y à H:i',
                        strtotime($message->getCreatedAt())
                    ) ?>
                </span>

                <div class="messaging_message messaging_message--sent">

                    <p>
                        <?= htmlspecialchars(
                            $message->getContent()
                        ) ?>
                    </p>

                </div>

            <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>