
/***************************************************************************
 * CLASS    : SGC_Search_Select
 *            Build the UI for searching and selecting sgc posts
 * REQUIRES : admin/js/sgc-search/agc-admin-search.js
 **************************************************************************/
// public list of objects that search select will access and update
const SGC_Search_Select_OBJECTS = Object.freeze({
    'items_selected':   'sgc_selected_items',       // TEXTAREA that will store the JSON list of selected items
    'output_summary':   'sgc_selected_summary',     // DIV that will display a summary of selected data
    'output_main':      'sgc_selected_main',        // DIV that will display selected data in the main search dialog
    'input_search':     'sgc_search_input',         // INPUT text that the user will enter the search string into
    'output_results':   'sgc_search_result',        // DIV where the results of the search will be displayed
    'btn_search':       'sgc_search_button',        // Search button
    'nav_start':        'sgc_page_nav_start',       // Search paging navigation go to first page
    'nav_previous':     'sgc_page_nav_previous',    // Search paging navigation go previous page
    'nav_next':         'sgc_page_nav_next',        // Search paging navigation go next page
    'nav_end':          'sgc_page_nav_end',         // Search paging navigation go to last page
    'nav_info':         'sgc_search_page_info'         // Search paging navigation display current page and total pages
});


class SGC_Search_Select extends SGC_Search {

    constructor(container='sgc_select_search', summary='sgc_select_summary', category=null, single=false) {
        super(category);

        this.container = container;
        this.summary = summary;
        this.selected_list = [];
        this.single = single;
        
        // Initialize search html objects
        let thisObject = this;
        jQuery( '#' + thisObject.container + ' #' + SGC_Search_Select_OBJECTS.btn_search ).click( function () {
            thisObject.run_search(); return false;
        });
        jQuery( '#' + thisObject.container + ' #' + SGC_Search_Select_OBJECTS.input_search ).change( function () {
            thisObject.run_search(); return false;
        });
        jQuery( '#' + thisObject.container + ' #' + SGC_Search_Select_OBJECTS.input_search ).on( 'input', function () {
            thisObject.run_search(); return false;
        });
        
        // Initalize paging objects
        jQuery( '#' + thisObject.container + ' #' + SGC_Search_Select_OBJECTS.nav_start ).click( function () {
            thisObject.goto_page_start();
            thisObject.run_search();
            return false;
        });
        jQuery( '#' + thisObject.container + ' #' + SGC_Search_Select_OBJECTS.nav_previous ).click( function () {
            thisObject.goto_page_previous();
            thisObject.run_search();
            return false;
        });
        jQuery( '#' + thisObject.container + ' #' + SGC_Search_Select_OBJECTS.nav_next ).click( function () {
            thisObject.goto_page_next();
            thisObject.run_search();
            return false;
        });
        jQuery( '#' + thisObject.container + ' #' + SGC_Search_Select_OBJECTS.nav_end ).click( function () {
            thisObject.goto_page_end();
            thisObject.run_search();
            return false;
        });
        
        // Initialize first search run
        this.init_selected_list();
        this.display_selected_lists();
        this.run_search();
    }

    /**
     * 
     * @param {type} list
     * @returns {Boolean}
     */
    init_selected_list() {     // TODO : validate incoming data
        this.selected_list = JSON.parse( jQuery('#' + this.container + ' #' + SGC_Search_Select_OBJECTS.items_selected ).html().trim() );
        return true;
    }

    /**
     * 
     * @returns {Array|Boolean|@var;list}
     */
    get_selected_list() {
        return this.selected_list;
    }

    /**
     * 
     * @returns {Boolean}
     */
    save_selected_list() {
        jQuery('#' + this.container + ' #' + SGC_Search_Select_OBJECTS.items_selected).html(JSON.stringify(this.selected_list));
        return false;
    }

    /**
     * 
     * @param {type} obj
     * @param {type} htmlobj
     * @returns {Boolean}
     */
    add_item( obj, htmlObj=SGC_Search_Select_OBJECTS.sgc_selected_main ) {
        let selected = {
            'ID' : jQuery('#' + this.container + ' #' + htmlObj).attr('item_id'),
            'name' : jQuery('#' + this.container + ' #' + htmlObj).attr('item_name'),
            'URL' : jQuery('#' + this.container + ' #' + htmlObj).attr('item_url'),
            'delete' : false
        };

        // UnDelete the item if it's already on the list
        let unDelete=false;
        let thisObject = this;
        obj.selected_list.forEach(function ( item ) {
            // If we're in single item mode, set delete for all items
            if( thisObject.single ) {
                item['delete'] = true;
            }
            // set undelte an item
            if( item['ID'] == selected.ID ) {
                unDelete=true;
                item['delete'] = false;
            }
        });

        // Add item if it's new
        if( !unDelete ) { obj.selected_list.push( selected ); };
        
        obj.save_selected_list();
        obj.display_selected( this.container, SGC_Search_Select_OBJECTS.output_main );
        obj.display_selected( this.summary, SGC_Search_Select_OBJECTS.output_summary );

        return false;
    }

    /**
     * 
     */
    delete_item(container, obj, htmlObj=SGC_Search_Select_OBJECTS.sgc_selected_main) {
        let item_id = jQuery('#' + container + ' #' + htmlObj).attr('item_id');

        // loop through the list of teams and remove the matching ID
        for (let i = 0; i < obj.selected_list.length; i++) {
            if (obj.selected_list[i].ID == item_id) {
                //obj.selected_list.splice(i, 1);
                obj.selected_list[i].delete = true;
                obj.save_selected_list();
            }
        }
        obj.display_selected(this.container, SGC_Search_Select_OBJECTS.output_main);
        obj.display_selected(this.summary, SGC_Search_Select_OBJECTS.output_summary);
        return false;
    }

    /**
     * 
     * @returns {undefined}
     */
    display_selected_lists() {
        this.display_selected( this.container, SGC_Search_Select_OBJECTS.output_main );
        this.display_selected( this.summary, SGC_Search_Select_OBJECTS.output_summary );
        return false;
    }

    /**
     * 
     * @param {type} htmlObj
     * @returns {Boolean}
     */
    display_selected(container, htmlObj=SGC_Search_Select_OBJECTS.output_main) {
        
        if( htmlObj == SGC_Search_Select_OBJECTS.output_summary && this.single ) {
            // Clear the existing list
            jQuery('#' + container + ' #' + htmlObj).html( '' );
            jQuery('#' + container + ' #' + htmlObj).val( '' );
            // insert the first element that has not been deleted
            this.selected_list.forEach(function ( item ) {
                if( !item['delete'] ) {
                    jQuery('#' + container + ' #' + htmlObj).html( item['name'] );
                    jQuery('#' + container + ' #' + htmlObj).val( item['name'] );
                }
            });
        } else {
            // Clear the existing list
            jQuery('#' + container + ' #' + htmlObj).html('<ul class="sgc-search-select-items" '
                    + 'id="' + htmlObj + '_list"></ul>');

            // Exit if empty
            if( this.selected_list.length < 1 ) { return false; }
            
            // Append the list of items
            let thisObject = this;
            let thisContainer = container;
            this.selected_list.forEach(function ( item ) {
                // Skip any empty items or items to be deleted
                
                if( item['ID'] != '' && ! item['delete'] ) {
                    jQuery('#' + thisContainer + ' #' + htmlObj + '_list').append(
                            '<li class="sgc-select-item"><a href="' + item['URL'] + '" target="SearchItem">'
                            + item['name'] + '</a>'
                            + '<small class="sgc-action" style="float: right"><a href="" id="' + htmlObj + '_delete_item_' + item['ID'] + '" item_id="'
                            + item['ID'] + '" class="sgc-button sgc-button-delete">' + SGCtxt.txt_remove + '</a></small></li>');

                    jQuery('#' + thisContainer + ' #' + htmlObj + '_delete_item_' + item['ID']).click(function () {
                        thisObject.delete_item(thisContainer, thisObject, htmlObj + '_delete_item_' + item['ID']);
                        return false; });
                }
            });
        }
        return false;
    }
    
    /**
     * 
     * @returns {Boolean}
     */
    run_search() {
        this.search_term( jQuery('#' + this.container + ' #' + SGC_Search_Select_OBJECTS.input_search).val() );
        jQuery('#' + this.container + ' #' + SGC_Search_Select_OBJECTS.btn_search).addClass('disabled');
        this.search();
        this.display_search_results();
        return false;
    }
    /**
     * 
     */
    async display_search_results(container, summary) {
        // Wait for the current search to finish
        while( this.running ) { await this.sleep( this.wait ); }

        // Clear the existing list
        jQuery('#' + this.container + ' #' + SGC_Search_Select_OBJECTS.output_results).html('<ul class="sgc-search-items" '
                + 'id="sgc_search_items"></ul>');

        // Parse the existing results
        let thisObject=this;
        this.results.forEach(function (item) {
            // Add each team to the list
            jQuery('#' + thisObject.container + ' #sgc_search_items').append('<li class="sgc-select-item">'
                    + '<a href="' + item.link
                    + '" target="Item">' + item.title.rendered + '</a>'
                    + '<small class="sgc-action" style="float: right">'
                    + '<a href="" id="' + SGC_Search_Select_OBJECTS.output_results + '_add_item_' + item.id + '" item_id="'
                    + item.id + '" item_name="' + item.title.rendered + '" item_url="'
                    + item.link + '" class="sgc-button">' + SGCtxt.txt_add + '</a></small></li>');

            // Bind events
            jQuery('#' + thisObject.container + ' #' + SGC_Search_Select_OBJECTS.output_results + '_add_item_' + item.id ).click(function () { 
                thisObject.add_item(thisObject, SGC_Search_Select_OBJECTS.output_results + '_add_item_' + item.id); 
                return false;
            });
        });
        
        // Update Search Navigation
        jQuery('#' + thisObject.ontainer + ' #' + SGC_Search_Select_OBJECTS.nav_info ).html(
            '(' + this.page + '/' + this.total_pages + ')');
    
        // enable search button
        jQuery('#' + this.container + ' #' + SGC_Search_Select_OBJECTS.btn_search).removeClass('disabled');
        
        return true;
    }

};
