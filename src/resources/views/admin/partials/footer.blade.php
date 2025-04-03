<footer class="footer">
    <div class="footer-content">
      <p class="copy-write">
        <a href="{{ url('/') }}" class="text-primary">{{ site_settings("copyright") }}</a>
      </p>
      <div class="footer-right">
        <ul>
          <li>
            <a href="https://support.igensolutionsltd.com">{{ translate("Support") }}</a>
          </li>
        </ul>
        <span class="i-badge info-soft dot">{{ translate("Version: ". site_settings('app_version')) }}</span>
      </div>
    </div>
  </footer>