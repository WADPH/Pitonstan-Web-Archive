// Supported languages in desired order.
var SUPPORTED_LANGS = ["en","ru","az"];

// Read language from URL (?lang=xx) or localStorage, else fallback "en".
function readLang() {
  var urlLang = new URLSearchParams(location.search).get("lang");
  if (SUPPORTED_LANGS.indexOf(urlLang) !== -1) return urlLang;
  var stored = localStorage.getItem("site_lang");
  if (SUPPORTED_LANGS.indexOf(stored) !== -1) return stored;
  return "en";
}

// Persist language and update <html lang="...">
function saveLang(lng) {
  localStorage.setItem("site_lang", lng);
  document.documentElement.setAttribute("lang", lng);
}

// Update/insert ?lang= in current URL without reload.
function setUrlLang(lng) {
  var u = new URL(location.href);
  u.searchParams.set("lang", lng);
  history.replaceState(null, "", u.toString());
}

// Init syncing on each page.
// applyFn = page-specific setLanguage(lang)
function initLang(applyFn) {
  var lng = readLang();
  saveLang(lng);
  setUrlLang(lng);

  if (typeof applyFn === "function") applyFn(lng);

  // hookup both dropdowns
  ["langSwitcher","langSwitcherMobile"].forEach(function(id){
    var el = document.getElementById(id);
    if (!el) return;
    el.value = lng;

    el.addEventListener("change", function(e){
      var v = e.target.value;
      if (SUPPORTED_LANGS.indexOf(v) === -1) return;

      saveLang(v);
      setUrlLang(v);
      if (typeof applyFn === "function") applyFn(v);

      // mirror value to the other selector
      ["langSwitcher","langSwitcherMobile"].forEach(function(otherId){
        if (otherId === id) return;
        var other = document.getElementById(otherId);
        if (other) other.value = v;
      });
    });
  });

  // propagate ?lang=... into internal links *.html
  Array.prototype.forEach.call(
    document.querySelectorAll('a[href$=".html"]'),
    function(a){
      try {
        var href = new URL(a.getAttribute("href"), location.href);
        href.searchParams.set("lang", lng);
        a.setAttribute("href", href.pathname + href.search + href.hash);
      } catch(e) {}
    }
  );
}
