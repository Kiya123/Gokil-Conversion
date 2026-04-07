<div class="arch-info">
  <div class="arch-node">
    <div class="node-dot client-dot"></div>
    <div class="node-info">
      <span class="node-label">Node 1 — Client</span>
      <span class="node-detail">Browser + PHP Server (localhost:3000)</span>
    </div>
  </div>
  <div class="arch-arrow">
    <div class="arrow-line"></div>
    <span class="arrow-label"><?= CONNECTION_MODE === 'ngrok' ? 'HTTPS · Ngrok' : 'HTTP · LAN' ?></span>
  </div>
  <div class="arch-node">
    <div class="node-dot server-dot"></div>
    <div class="node-info">
      <span class="node-label">Node 2 — Server</span>
      <span class="node-detail"><?= htmlspecialchars(ACTIVE_SERVER_LABEL) ?></span>
    </div>
  </div>
</div>
