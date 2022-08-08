# Google Reviews
Use the Google Places API to display Google business reviews on your website.

**Note:** The JS was originally sourced from [Peledies](https://github.com/peledies) Google Places jQuery Plugin [(here)](https://github.com/peledies/google-places) although I've tweaked it a little with additional schema, user avatars, less convoluted mark-up and limited the functionality to only show reviews.

The file (reviews.php) is more contained (no external JS, aside from the jQuery requirement) and also inludes a simple configuration via a bunch of PHP variables. You could just make it a server side include and not have to think about it.

**Requirements:** Google maps API key, with Maps JavaScript API and Places API both enabled via your [Google API console](https://console.cloud.google.com/apis).

**Pro tips:** 

* Get your place ID from [here](https://developers.google.com/maps/documentation/javascript/examples/places-placeid-finder).

* Remove `<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>` from reviews.php if your project already uses jQuery. It's only included in this repo so you can quickly see it working.

## Limitations ##

The Google places API will only allow you to return a maximum of five reviews, and these will always be limited to the reviews Google says are 'Most Relevant' (you can't show the most recent for example).

This is confounded with another problem: If you want to return the five most relevant reviews that have five stars, but one of the reviews among the most relevant only has four stars, then only four reviews will be returned.

# License

MIT FTW! (https://opensource.org/licenses/MIT)
