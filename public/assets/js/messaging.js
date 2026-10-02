document.addEventListener("DOMContentLoaded", () => {
  const messagesContainer = document.getElementById("messaging-messages");

  if (!messagesContainer) {
    return;
  }

  const urlParams = new URLSearchParams(window.location.search);
  const conversationId = urlParams.get("id");

  if (!conversationId) {
    return;
  }

  const refreshMessages = async () => {
    try {
      const messageElements =
        messagesContainer.querySelectorAll("[data-message-id]");

      const lastMessageElement = messageElements[messageElements.length - 1];

      const lastMessageId = lastMessageElement
        ? lastMessageElement.dataset.messageId
        : 0;

      const response = await fetch(
        `index.php?route=message-refresh&id=${conversationId}&last_message_id=${lastMessageId}`,
      );

      if (!response.ok) {
        return;
      }

      const html = await response.text();

      if (html.trim() !== "") {
        messagesContainer.insertAdjacentHTML("beforeend", html);
      }
    } catch (error) {
      return;
    }
  };

  const scheduleRefresh = async () => {
    await refreshMessages();

    setTimeout(scheduleRefresh, 3000);
  };

  scheduleRefresh();
});
