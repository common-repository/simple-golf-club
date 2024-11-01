/***************************************************************************
* CLASS    : SGC_Search
*            Parent class to provide search functionality for SGC posts APIs
* REQUIRES :
**************************************************************************/

// Public list of categories supported by SGC_Search
const SGC_Search_CATEGORIES = Object.freeze({
    'locations'     :   0,
    'teams'         :   1,
    'players'       :   2,
    'events'        :   3,
    'scorecards'    :   4
    });


class SGC_Search {

    constructor ( category=null ) {
        this.results = [];
        this.category = {};
        this.category_attr = [
            {'name': 'Locations',   'api': '/wp-json/wp/v2/sgc_locations'} ,
            {'name': 'Teams',       'api': '/wp-json/wp/v2/sgc_teams'} ,
            {'name': 'Players',     'api': '/wp-json/wp/v2/sgc_players'} ,
            {'name': 'Events',      'api': '/wp-json/wp/v2/sgc_events'} ,
            {'name': 'Scorecards',  'api': '/wp-json/wp/v2/sgc_scorecards'} 
        ];

        // Search Info
        this.query = '';
        this.perpage = 16;
        this.page = 1;
        this.total_pages = 0;
        this.total_elements = 0;
        this.running = false;
        this.wait = 64;
        
        // Configure Category
        switch ( category ) {
            case SGC_Search_CATEGORIES.locations :
                this.category = this.category_attr[SGC_Search_CATEGORIES.locations];
                break;
            case SGC_Search_CATEGORIES.teams :
                this.category = this.category_attr[SGC_Search_CATEGORIES.teams];
                break;
            case SGC_Search_CATEGORIES.players :
                this.category = this.category_attr[SGC_Search_CATEGORIES.players];
                break;
            case SGC_Search_CATEGORIES.events :
                this.category = this.category_attr[SGC_Search_CATEGORIES.events];
                break;
            case SGC_Search_CATEGORIES.scorecards :
                this.category = this.category_attr[SGC_Search_CATEGORIES.scorecards];
                break;
            default :
                console.log('SGC_Search - Unknown Category : \'' + category + '\'');
        }

    }
    
    /**
     * run_search : Queary the REST API to get the array of results
     * @returns {Boolean}
     */
    search() {
        if( ! this.running && this.category.api ) {
            this.running = true;
            
            // Build Arguments
            let args='?order=asc&orderby=title'
                + '&per_page=' + this.perpage
                + '&page=' + this.page
                + '&search=' + this.query;
    
            // fetch list
            let thisObject = this;
            jQuery.ajax({
               method: 'GET',
               url: SGCtxt.url_site + this.category.api + args,
               success: function (result, textStatus, request) {
                    thisObject.results = result;

                    // Update header info
                    thisObject.total_pages = request.getResponseHeader('X-WP-TotalPages');
                    thisObject.total_elements = request.getResponseHeader('X-WP-Total');

                    // Search complete
                    thisObject.running = false;
                }
            });
        } else {
            return false;
        }
        return true;
    }
    
    /**
     * search_tearm : update the search tearm
     * @param {string} sterm
     * @returns {Boolean}
     */
    search_term( sterm='' ) {
        this.query = sterm;
        return true;
    }

    /**
     * get_results : Return the current search results. Return FALSE if a search is still running.
     * @returns {Array|Boolean}
     */
    get_results() {
        if( this.running ) { return false; }
        return this.results;
    }

    /**
     * 
     * @returns {Boolean}
     */
    goto_page_start() {
        this.page = 1;
        return true;
    }
    /**
     * 
     * @returns {Boolean}
     */
    goto_page_end() {
        this.page = this.total_pages;
        return true;
    }
    /**
     * 
     * @returns {Boolean}
     */
    goto_page_previous() {
        if( this.page <= 1 ) { return false; }
        this.page--;
        return true;
    }
    /**
     * 
     * @returns {Boolean}
     */
    goto_page_next() {
        if( this.page >= this.total_pages ) { return false; }
        this.page++;
        return true;
    }
    
    /**
     * sleep : This will allow us to sleep while waiting for return values in async functions.
     * @param {int} ms
     * @returns {Promise}
     */
    sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

}