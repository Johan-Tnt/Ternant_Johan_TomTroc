<section>

    <div class="container">

        <div class="messaging_content">

           <!-- LISTE DES CONVERSATIONS -->
            <aside class="messaging_conversations">

                <div class="messaging_conversations_header">
                    <h1>Messagerie</h1>
                </div>

                <div class="messaging_conversations_list">

                    <?php if (empty($conversations)) : ?>

                        <p class="messaging_empty">
                            Vous n'avez aucune conversation.
                        </p>
                    
                    <?php else : ?>

                        <?php foreach ($conversations as $conversationData) : ?>

                            <a 
                                href="index.php?route=messaging&id=<?= (int) $conversationData['id'] ?>"
                                class="messaging_conversation"
                            >

                                <div class="messaging_avatar">
                                    <img 
                                        src="assets/images/avatars/<?= htmlspecialchars(
                                            $conversationData['other_user_avatar'] ?: 'default-avatar.jpg'
                                        ) ?>"
                                        alt="Photo de <?= htmlspecialchars(
                                            $conversationData['other_user_pseudo']
                                        ) ?>"
                                    >
                                </div>

                                <div class="messaging_conversation_content">

                                    <div class="messaging_conversation_header">

                                        <span class="messaging_pseudo">
                                           <?= htmlspecialchars(
                                            $conversationData['other_user_pseudo']
                                           ) ?>
                                        </span>

                                        <?php if (!empty($conversationData['last_message_created_at'])) : ?>

                                        <span class="messaging_time">
                                            <?= date(
                                                'd/m/Y à H:i',
                                                strtotime($conversationData['last_message_created_at'])
                                            ) ?>
                                        </span>

                                        <?php endif; ?>

                                    </div>

                                    <?php
                                    $preview = $conversationData['last_message_content'] ?? 'Aucun message';

                                    if (mb_strlen($preview) > 30) {
                                        $preview = mb_substr($preview, 0, 30);
                                        $preview = mb_substr($preview, 0, mb_strrpos($preview, ' ')) . '...';
                                    }
                                    ?>

                                    <p class="message_preview">

                                        <?= htmlspecialchars($preview) ?>
                                        
                                    </p>

                                </div>

                            </a>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </aside>

            <!-- CONVERSATION -->
            <section class="messaging_conversation_view">

            <?php if ($conversation !== null) : ?>

                <?php 
                //Recherche les informations du correspondant
                $selectedConversation = null;

                foreach ($conversations as $conversationData) {
                    if ((int) $conversationData['id'] === $conversation->getId()) {
                        $selectedConversation = $conversationData;
                        break;
                    }
                }
                ?>

                <div class="messaging_conversation_top">

                    <div class="messaging_avatar">
                        
                        <img 
                           src="assets/images/avatars/<?= htmlspecialchars(
                                $selectedConversation['other_user_avatar']
                                ?: 'default-avatar.jpg'
                           ) ?>"
                           alt="Photo de <?= htmlspecialchars(
                                $selectedConversation['other_user_pseudo']
                           ) ?>"
                        >

                    </div>

                    <span class="messaging_pseudo">

                        <?=  htmlspecialchars(
                            $selectedConversation['other_user_pseudo']
                        ) ?>

                    </span>

                </div>

                <div class="messaging_messages" id="messaging-messages">

                    <?php require __DIR__ . '/Partials/messages.php'; ?>

                </div>

                <form
                    method="POST"
                    action="index.php?route=messaging&id=<?= $conversation->getId() ?>"
                    class="messaging_form"
                >

                <input
                    type="text"
                    name="content"
                    placeholder="Tapez votre message ici"
                    class="messaging_input"
                >

                <button
                    type="submit"
                    class="button button--primary messaging_button"
                >
                    Envoyer
                </button>

                </form>

            <?php else : ?>

                <div class="messaging_conversation_top">

                    <div class="messaging_avatar">

                        <img
                            src="assets/images/avatars/default-avatar.jpg"
                            alt=""
                        >

                    </div>

                    <span class="messaging_pseudo">
                        Aucune conversation sélectionnée
                    </span>

                </div>

                <div class="messaging_messages">

                    <p class="messaging_empty">
                        Sélectionnez une conversation pour afficher les messages.               
                    </p>

                </div>

            <?php endif; ?>

            </section>

        </div>

    </div>

</section>