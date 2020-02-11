<?php
$token = $embed->getAccessToken();
$access_token = $token['access_token'];
?>
<script>
    (function(w, d, s, g, js, fs) {
        g = w.gapi || (w.gapi = {});
        g.analytics = {
            q: [],
            ready: function(f) {
                this.q.push(f);
            }
        };
        js = d.createElement(s);
        fs = d.getElementsByTagName(s)[0];
        js.src = 'https://apis.google.com/js/platform.js';
        fs.parentNode.insertBefore(js, fs);
        js.onload = function() {
            g.load('analytics');
        };
    }(window, document, 'script'));
</script>
<div id="view-selector-container"></div>
<div class="row">
    <div class="col-md-6">
        <h5>Sessions</h5>
        <div id="session-chart-container">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <h5>Users</h5>
        <div id="user-chart-container">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <h5>Sessions By Country</h5>
        <div id="session-chart-by-country-container">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <h5>Page Visits</h5>
        <div id="page-chart-container">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <h5>Top Browsers</h5>
        <div id="browser-chart-container">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    gapi.analytics.ready(function() {

        /**
         * Authorize the user with an access token obtained server side.
         */
        gapi.analytics.auth.authorize({
            'serverAuth': {
                'access_token': '<?= $access_token ?>'
            }
        });

        /**
         * Creates a new DataChart instance showing sessions over the past 30 days.
         * It will be rendered inside an element with the id "chart-1-container".
         */
        var sessionChart = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:206580423', // <-- Replace with the ids value for your view.
                'start-date': '30daysAgo',
                'end-date': 'today',
                'metrics': 'ga:sessions',
                'dimensions': 'ga:date'
            },
            chart: {
                'container': 'session-chart-container',
                'type': 'LINE',
                'options': {
                    'width': '100%'
                }
            }
        });
        sessionChart.execute();

        var userChart = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:206580423', // <-- Replace with the ids value for your view.
                'start-date': '30daysAgo',
                'end-date': 'today',
                'metrics': 'ga:users',
                'dimensions': 'ga:date'
            },
            chart: {
                'container': 'user-chart-container',
                'type': 'LINE',
                'options': {
                    'width': '100%'
                }
            }
        });
        userChart.execute();

        var sessionChartByCountry = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:206580423',
                metrics: 'ga:sessions',
                dimensions: 'ga:country',
                'start-date': '30daysAgo',
                'end-date': 'today',
                'max-results': 6,
                sort: '-ga:sessions'
            },
            chart: {
                container: 'session-chart-by-country-container',
                type: 'PIE',
                options: {
                    width: '100%',
                    pieHole: 4 / 9
                }
            }
        });

        sessionChartByCountry.execute();


        /**
         * Creates a new DataChart instance showing top 5 most popular demos/tools
         * amongst returning users only.
         * It will be rendered inside an element with the id "chart-3-container".
         */
        var pageChart = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:206580423', // <-- Replace with the ids value for your view.
                'start-date': '30daysAgo',
                'end-date': 'yesterday',
                'metrics': 'ga:pageviews',
                'dimensions': 'ga:pagePath',
                'sort': '-ga:pageviews',
                //'filters': 'ga:pagePathLevel1!=/',
                'max-results': 7
            },
            chart: {
                'container': 'page-chart-container',
                'type': 'PIE',
                'options': {
                    'width': '100%',
                    'pieHole': 4 / 9,
                }
            }
        });
        pageChart.execute();

        var browserChart = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:206580423', // <-- Replace with the ids value for your view.
                'start-date': '30daysAgo',
                'end-date': 'yesterday',
                'metrics': 'ga:pageviews',
                'dimensions': 'ga:browser',
                'sort': '-ga:pageviews',
                //'filters': 'ga:pagePathLevel1!=/',
                'max-results': 7
            },
            chart: {
                'container': 'browser-chart-container',
                'type': 'PIE',
                'options': {
                    'width': '100%',
                    'pieHole': 4 / 9,
                }
            }
        });
        browserChart.execute();

    });
</script>