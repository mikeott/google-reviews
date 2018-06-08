# Google Reviews
Use the Google Places API to display Google business reviews on your website.

**Note:** The JS was originally sourced from [Peledies](https://github.com/peledies) Google Places jQuery Plugin [(here)](https://github.com/peledies/google-places) although I've tweaked it a little with additional schema, user avatars, less convoluted mark-up and limited the functionality to only show reviews.

The file (reviews.php) is more contained (no external JS, aside from the jQuery requirement) and also inludes a simple configuration via a bunch of PHP variables. You could just make it a server side include and not have to think about it.

**Requirements:** Google maps API key, with Maps JavaScript API and Places API both enabled via your [Google API console](https://console.cloud.google.com/apis).

**Pro tips:** 

..* Get your place ID from [here](https://developers.google.com/places/place-id).
..* Remove `<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>` if your project already uses jQuery.

## Limitations ##

The API will only allow you to return a maximum of five reviews, and these will always be limited to the reviews Google says are 'Most Relevant'.

This is confounded with another problem: If you want to return five reviews, and only reviews that have five stars, and one of the reviews only has four stars, then only four reviews will be returned.

**For example:** As you can see in the [Perth Airport Google Reviews](https://goo.gl/vYCorg) (screenshot below), when sorted by 'Most Relevant' only two of the first five reviews have 5 Stars. And so if you want to only show reviews with 5 Stars, then only two reviews will be returned. It appears that for whatever reason the Places API won't attempt to look beyond the five most relevant reviews. In a situation like this it may be preferable to make the $min_star value 3 instead, which means the API will look for all 'Most Relevant' reviews from 3 to 5 Stars (which in the case of Perth Airport below would mean three reviews would be returned).

![Screenshot](https://raw.githubusercontent.com/mikeott/google-reviews/master/doc-images/reviews.png)

## Todo ##

Presentation layer.

## Styled example ##

An example of how you could style it:

![Styled](https://raw.githubusercontent.com/mikeott/google-reviews/master/doc-images/example-styled.png)
