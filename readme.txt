=== Advanced Exchange Rates for WooCommerce Multilingual ===
Contributors: vyskoczilova, freemius
Tags: woocommerce, multilingual, wpml, exchange rates, currency
Donate: https://www.paypal.me/KarolinaVyskocilova
Requires at least: 4.6
Tested up to: 5.7
Requires PHP: 5.3
Stable tag: trunk

Adds additional exchange rates for WooCommerce Multilingual - currently European Central Bank (via exchangeratesapi.io).

== Description ==

**The development of this plugin is currently stopped due to changes in WCML. The Exchange rates API stopped the free plan and in the next WCML release 4.12 will be part of the WCML and they will change the code around it as well. I'm still thinking what to do with a plugin - if someone could sponsor the development, I'm open to rewriting it to be compatible with the new version & add free API, otherwise I'll close it. Thanks for understanding.**

This plugin extends the functionality of [WooCommerce Multilingual](https://wordpress.org/plugins/woocommerce-multilingual/) for automated exchange rates by adding additional exchange rates services:

* [European Central Bank](https://www.ecb.europa.eu/stats/policy_and_exchange_rates/euro_reference_exchange_rates/html/index.en.html) - rates are obtained from free & opensource service [Exchange Rates API](https://exchangeratesapi.io/) by [Madis Väin](https://github.com/madisvain); no API code or registration required.

Other integrations & features will come soon.

= This plugin solves multiple problems: =
* The base currency used in your shop is not USD - you can't use fixer.io or currencylayer.com service for free
* You don't need a real-time exchange rate
* You need to do foreign exchange accounting properly - using a bank's exchange rate

= Requires following plugins =
* [WooCommerce Multilingual](https://wordpress.org/plugins/woocommerce-multilingual/) 4.6.7 or higher
* [WooCommerce](https://wordpress.org/plugins/woocommerce/)
* [WPML](https://wpml.org/)


== Installation ==

= 1. Install the plugin =

The latest versions are always available in the WordPress Repository, and you can choose one of your favorite ways to install it:

* automatically using [built-in plugin installer](https://codex.wordpress.org/Managing_Plugins#Automatic_Plugin_Installation) (recommended)
* manually by [uploading a zip archive](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation_by_FTP)
* manually by [FTP](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation_by_Uploading_a_Zip_Archive)

= 2. Complete the setup =

Select the newly added service in `WooCommerce -> WooCommerce Multilingual -> Multi curency`.

1. Enable `Enable automatic exchange rates`
1. Select the newly added service - European Central Bank - in `Exchange rates source`.
1. Select update frequency - recommended is `daily`.
1. Save the settings (otherwise the rate will be changed by following step but you would need to refresh the page in order to see it).
1. Now you could click on `update manually now` button to update the rates for the first time.


== Frequently Asked Questions ==

= What about my data & privacy? =

The list of currencies used on your website is used to call to exchange rate service to get the exchange rate back. Your store's data will not be transmitted. All data (e.g., price conversion itself) is still handled by [WooCommerce Multilingual](https://wordpress.org/plugins/woocommerce-multilingual/).

If you opt-in to Freemius annonymous usage tracking following [Terms of Service](https://freemius.com/wordpress/usage-tracking/3314/advanced-exchange-rates/) & [Privacy Policy](https://freemius.com/privacy/) will be used.



== Screenshots ==

1. Newly added Exchange rate service


== Upgrade Notice ==


== Changelog ==

= 1.0.6 (2021-02-22) =

* Update Freemius SDK to version [2.4.2](https://github.com/Freemius/wordpress-sdk/releases/tag/2.4.2)

= 1.0.5 (2020-11-10) =
* Update Freemius SDK to version [2.4.1](https://github.com/Freemius/wordpress-sdk/releases/tag/2.4.1)
    * Whitelabeling
    * jQuery update
    * Auto-updates

= 1.0.4 (2019-09-11) =
* Update check for WPML Multilingual version

= 1.0.3 (2019-08-01) =
* Small refactoring


= 1.0.2 (2019-07-05) =
* Update Freemius SDK to 2.3.0

= 1.0.1 (2019-03-02) =
* **Bug Fixes**
	* Updated Freemius SDK which includes a security fix.

= 1.0.0 (2019-02-19) =
* Initial release
