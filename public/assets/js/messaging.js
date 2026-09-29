document.addEventListener("DOMContentLoaded", () => {
  const messagesContainer = document.querySelector("#messaging-messages");

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
      const response = await fetch(
        `index.php?route=message-refresh&id=${conversationId}`,
      );

      if (!response.ok) {
        return;
      }

      const html = await response.text();

      messagesContainer.innerHTML = html;
    } catch (error) {
      return;
    }
  };

  setInterval(refreshMessages, 3000);
});
