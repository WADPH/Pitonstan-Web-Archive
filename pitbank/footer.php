<?php $year = date('Y'); ?>
<style>
.footer {
    background: var(--card);
    color: var(--text);
    border-top: 1px solid var(--border);
    margin-top: auto;
}
.footer-inner {
    max-width: 1200px; margin: 0 auto; padding: 40px 20px;
    display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;
}
.footer h4 { margin-bottom: 12px; color: var(--secondary); }
.footer p, .footer li, .footer a { color: var(--muted); font-size: 14px; line-height: 1.5; text-decoration: none; }
.footer ul { list-style: none; padding: 0; }
.footer a:hover { color: var(--primary); }
.footer-bottom {
    border-top: 1px solid var(--border);
    padding: 16px 20px; text-align: center; font-size: 13px; color: var(--muted);
}
.brand-badge {
    display: inline-flex; align-items: center; gap: 10px; font-weight: 700; color: var(--text);
}
.brand-badge img { height: 22px; width: auto; }
</style>

<footer class="footer">
  <div class="footer-inner">
    <div>
      <div class="brand-badge">
        <img src="img/logo.png" alt="PitBank">
        <span>PitBank</span>
      </div>
      <p><?= __t('footer_about_text') ?></p>
    </div>
    <div>
      <h4><?= __t('footer_products') ?></h4>
      <ul>
        <li><?= __t('footer_products_list') ?></li>
      </ul>
    </div>
    <div>
      <h4><?= __t('footer_support') ?></h4>
      <ul>
        <li><?= __t('footer_support_list') ?></li>
      </ul>
    </div>
    <div>
      <h4><?= __t('footer_legal') ?></h4>
      <ul>
        <li><?= __t('footer_legal_list') ?></li>
      </ul>
    </div>
    <div>
      <h4><?= __t('footer_contact') ?></h4>
      <ul>
        <li><?= __t('footer_contact_list') ?></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    © <?= $year ?> PitBank. <?= __t('footer_rights') ?>
  </div>
</footer>
