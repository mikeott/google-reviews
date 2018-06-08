<?php 
    /*
        Configuration.
        Note: You'll need a Google maps API key - requires both Maps JS and Places API services enabled.
    */

    $place_ID       = ''; // Get from: https://developers.google.com/places/place-id
    $business_type  = ''; // Example: FinancialService (http://schema.org)
    $business_name  = '';
    $street_address = '';
    $locality       = ''; // Example: Docklands (http://schema.org/addressLocality)
    $region         = '';
    $post_code      = '';
    $logo_path      = 'images/system/logo.png';
    $min_star       = '1'; // The minimum star rating (min = 1)
    $max_rows       = '5'; // The maximum number of results (max = 5)
    $api_key        = '';
?>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=<?php echo $api_key; ?>&libraries=places"></script>
<script>
    (function($) {

    $.googlePlaces = function(element, options) {

        var defaults = {
            placeId:        '<?php echo $place_ID; ?>', 
            render:         ['reviews'], 
            min_rating:     <?php echo $min_star; ?>, 
            max_rows:       <?php echo $max_rows; ?>, 
            rotateTime:     false
        };

        var plugin = this;

        plugin.settings = {}

        var $element = $(element),
            element = element;

        plugin.init = function() {
        plugin.settings = $.extend({}, defaults, options);
        $element.html("<div id='reviews'></div>"); // create a plug for google to load data into
        initialize_place(function(place){
            plugin.place_data = place;
            // render specified sections
            if(plugin.settings.render.indexOf('reviews') > -1){
            renderReviews(plugin.place_data.reviews);
            if(!!plugin.settings.rotateTime) {
                initRotation();
            }
            }
        });
        }

        var initialize_place = function(c){
        var map = new google.maps.Map(document.getElementById('reviews'));

        var request = {
            placeId: plugin.settings.placeId
        };

        var service = new google.maps.places.PlacesService(map);

        service.getDetails(request, function(place, status) {
            if (status == google.maps.places.PlacesServiceStatus.OK) {
            c(place);
            }
        });
        }

        var sort_by_date = function(ray) {
        ray.sort(function(a, b){
            var keyA = new Date(a.time),
            keyB = new Date(b.time);
            // Compare the 2 dates
            if(keyA < keyB) return -1;
            if(keyA > keyB) return 1;
            return 0;
        });
        return ray;
        }

        var filter_minimum_rating = function(reviews){
        for (var i = reviews.length -1; i >= 0; i--) {
            if(reviews[i].rating < plugin.settings.min_rating){
                reviews.splice(i,1);
            }
        }
        return reviews;
        }

        var renderReviews = function(reviews) {
            reviews = sort_by_date(reviews);
            reviews = filter_minimum_rating(reviews);
            var html = "";
            var row_count = (plugin.settings.max_rows > 0)? plugin.settings.max_rows - 1 : reviews.length - 1;
            // make sure the row_count is not greater than available records
            row_count = (row_count > reviews.length-1)? reviews.length -1 : row_count;
            for (var i = row_count; i >= 0; i--) {
                var stars = renderStars(reviews[i].rating);
                var date = convertTime(reviews[i].time);
                html = html+"<div class='review-item' itemprop='review' itemscope itemtype='http://schema.org/Review'><img src='"+reviews[i].profile_photo_url+"'/><div class='review-inner'><meta itemprop='itemReviewed' content='http://schema.org/<?php echo $business_type; ?>' /><p class='review-text' itemprop='description'>"+reviews[i].text+"</p><div class='review-meta'><span class='review-author' itemprop='author'>"+reviews[i].author_name+"</span><span class='review-sep'>, </span><span class='review-date' itemprop='datePublished'>"+date+"</span></div>"+stars+"</div></div>"
            };
            $element.append(html);
        }
        
        var initRotation = function() {
        var $reviewEls = $element.children('.review-item');
        var currentIdx = $reviewEls.length > 0 ? 0 : false;
        $reviewEls.hide();
        if(currentIdx !== false) {
            $($reviewEls[currentIdx]).show();
            setInterval(function(){ 
            if(++currentIdx >= $reviewEls.length) {
                currentIdx = 0;
            }
            $reviewEls.hide();
            $($reviewEls[currentIdx]).fadeIn('slow');
            }, plugin.settings.rotateTime);
        }
        }

        var renderStars = function(rating) {
            var stars = "<div class='review-stars' itemprop='reviewRating' itemscope itemtype='http://schema.org/Rating'><meta itemprop='worstRating' content='1'/><meta itemprop='ratingValue' content='" + rating + "'/><meta itemprop='bestRating' content='5'/><ul>";

            // fill in gold stars
            for (var i = 0; i < rating; i++) {
                stars = stars+"<li class='star'>&#9733;</li>";
            };

            // fill in empty stars
            if(rating < 5){
                for (var i = 0; i < (5 - rating); i++) {
                    stars = stars+"<li class='star inactive'>&#9733;</li>";
                };
            }
            stars = stars+"</ul></div>";
            return stars;
        }

        var convertTime = function(UNIX_timestamp) {
            var a = new Date(UNIX_timestamp * 1000);
            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            var time = months[a.getMonth()] + ' ' + a.getDate() + ', ' + a.getFullYear();
            return time;
        }

        plugin.init();

    }

    $.fn.googlePlaces = function(options) {

        return this.each(function() {
        if (undefined == $(this).data('googlePlaces')) {
            var plugin = new $.googlePlaces(this, options);
            $(this).data('googlePlaces', plugin);
        }
        });

    }

    })(jQuery);
</script>

<meta itemscope itemtype="http://schema.org/<?php echo $business_type; ?>" />
<meta itemprop="name" content="<?php echo $business_name; ?>" />
<meta itemprop="image" content="<?php echo $logo_path; ?>" />
<meta itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"/>
<meta itemprop="streetAddress" content="<?php echo $street_address; ?>"/>
<meta itemprop="addressLocality" content="<?php echo $locality; ?>"/>
<meta itemprop="addressRegion" content="<?php echo $region; ?>"/>
<meta itemprop="postalCode" content="<?php echo $post_code; ?>"/>
<div id="google-reviews"></div>

<script>
    $(document).ready(function() {
        $("#google-reviews").googlePlaces({});
    });
</script>
