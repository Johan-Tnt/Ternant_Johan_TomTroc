document.addEventListener("DOMContentLoaded", () => {
  const unreadMessageCount = document.querySelector("#messaging-unread-count");

  if (!unreadMessageCount) {
    return;
  }

  const refreshUnreadMessageCount = async () => {
    try {
      const response = await fetch("index.php?route=message-count");

      if (!response.ok) {
        return;
      }

      const data = await response.json();

      if (data.count > 0) {
        unreadMessageCount.textContent = data.count;
        unreadMessageCount.hidden = false;
      } else {
        unreadMessageCount.hidden = true;
      }
    } catch (error) {
      return;
    }
  };

  const scheduleUnreadMessageCountRefresh = async () => {
    await refreshUnreadMessageCount();

    setTimeout(scheduleUnreadMessageCountRefresh, 10000);
  };

  scheduleUnreadMessageCountRefresh();
});
