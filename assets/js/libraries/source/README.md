# Unminified sources for the bundled libraries

The files that Black Widgets actually enqueues live one level up, in
`assets/js/libraries/` and `assets/css/libraries/`. Some of them are the
production (minified) builds. This folder holds the matching human-readable
source for each of them, so the plugin satisfies the WordPress.org requirement
that all shipped code stays auditable.

Nothing in this folder is enqueued at runtime. Every handle in `src/main.php`
and in the widget constructors points at an explicit file path one level up, so
adding or removing files here cannot affect the front end.

| Enqueued file | Source here | Version | License | Upstream |
| --- | --- | --- | --- | --- |
| `assets/js/libraries/anime.js` | `anime.js` | 3.1.0 | MIT | https://github.com/juliangarnier/anime/releases/tag/v3.1.0 |
| `assets/js/libraries/simple-parallax.js` | `simple-parallax.js` | 5.2.0 | MIT | https://github.com/geosigno/simpleParallax.js |
| `assets/js/libraries/swiper-bundle.min.js` | `swiper-bundle.js` | 11.1.14 | MIT | https://github.com/nolimits4web/swiper/releases/tag/v11.1.14 |
| `assets/css/libraries/swiper-bundle.min.css` | `../../../css/libraries/source/swiper-bundle.css` | 11.1.14 | MIT | https://github.com/nolimits4web/swiper/releases/tag/v11.1.14 |
| `assets/js/libraries/tilt.js` | `tilt.jquery.js` | 1.2.1 | MIT | https://github.com/gijsroge/tilt.js |

## Note on `tilt.js`

The enqueued `assets/js/libraries/tilt.js` is a minified Tilt.js build that does
not byte-match any published release, so treat `tilt.jquery.js` here as the
upstream reference rather than the exact preimage. The two known differences are
a re-encoded author line in the comment header (`Gijs Rogé` was mangled by a
charset conversion) and a trailing `$('[data-tilt]').tilt()` auto-init call,
which upstream leaves to the caller.

Because of that auto-init, any element with a `data-tilt` attribute is
initialised on pages where Tilt.js loads, which since 1.4.0 means pages using the
Black Button widget (plus the Elementor editor preview).

## GSAP

GSAP is deliberately **not** bundled. It is loaded from a CDN URL entered in
Black Widgets → Settings, so no GSAP code ships with this plugin.
