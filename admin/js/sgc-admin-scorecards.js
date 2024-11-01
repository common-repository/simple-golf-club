/**
 * Scorecard cutom post type javascript class
 */

(function ($) {
    'use strict';
    
    class SGC_Scorecard {

        constructor() {
            // Init Event Search
            this.select_events = new SGC_Search_Select( 'sgc_scorecard_manage_events_selectsearch', 'sgc_selected_events_summary', SGC_Search_CATEGORIES.events, true );
            
            SGC_Scorecard.event = [];
            SGC_Scorecard.tees_list = [];
        }

        /**
         * Hook into the publish button to preform actions before the post is published
         */
        publish_hook() {
            let sel_event = $("#sgc_selected_summary").val().trim();
            let sel_player = $("#sgc_scorecard_player").find('option:selected').text().trim();
            
            // Make sure an Event is set
            if ( "" === SGC_Scorecard.event.ID ) {
                alert(SGCtxt.txt_selectevent);
                return false;
            }
            
            // Set the title with "event (date time) - player
            let timestamp = new Date( Date.parse($('#sgc_scorecard_timestamp').val().trim()) );
            $('#title').val( sel_event 
                    + ' (' + timestamp.toLocaleDateString( SGCtxt.locale, {timeZone: SGCtxt.timezone} )
                    + ' ' + timestamp.toLocaleTimeString( SGCtxt.locale, {timeZone: SGCtxt.timezone} ) + ')' 
                    + ' - ' + sel_player );

            // Fill sgc_scorecardstrokes with a JSON array of strokes
            let arr_content = [];
            $('#sgc_scorecard_strokes').text('');
            $('form').each(function () {
                $(this).find(':input').each(function () {
                    if ($(this).attr('name') === 'sgc_scorecard_hole') {
                        arr_content.push($(this).val());
                    }
                });
            });
            $('#sgc_scorecard_strokes').text(JSON.stringify(arr_content));
        }

        /**
         * Update the list of events
         */
        static load_event() {
            SGC_Scorecard.event = [];
            let events = JSON.parse($('textarea[name=sgc_scorecard_events]').text().trim() );
            events.some( function(event) {
                if( event.delete === false ) {
                    SGC_Scorecard.event = event ;
                }
            });
        }
        
        /**
         * Update tee list
         */
        static load_tee_list() {    
            SGC_Scorecard.tees_list = [];
            $.getJSON(SGCtxt.url_site + SGCtxt.url_rest + '/event/tees/' 
                    + SGC_Scorecard.event.ID).done( function( result ) {
                // Update SGC_Scorecard.tees_list
                if( "success" === result.status ) {
                    SGC_Scorecard.tees_list = result.data ;
                    SGC_Scorecard.render_tee_list();
                }
            });
        }
        
        /**
         * Render the tee list form element
         */
        static render_tee_list() {
            $( '#sgc_scorecard_tee' ).html('<option value="">' + SGCtxt.opt_anytee + '</option>');

            SGC_Scorecard.tees_list.forEach(function( tee ) {
                // Update the tee list
                $( '#sgc_scorecard_tee' ).append('<option value="' 
                        + tee.color + '" ' + (( tee.color === $('#sgc_scorecard_selected_tee').val() ) ? 'selected' : '') + '>' 
                        + tee.color + ' (' + tee.difficulty + ')' + ((tee.isdefault) ? ' *' : '') 
                        + '</option>');
            }); 
        }
        
        /**
         * Update the tee list using selected location
         */
        display_tee_list() {
            SGC_Scorecard.load_tee_list();
        }
        
        /**
         * Render the player list form element
         */
        static render_player_list() {
            $( '#sgc_scorecard_player' ).html('<option value="">' + SGCtxt.opt_selectplayer + '</option>');
            let selected = $('#sgc_scorecard_selected_player').val();
            // Fetch the Players list
            $.getJSON(SGCtxt.url_site + SGCtxt.url_rest + '/event/players/' 
                    + SGC_Scorecard.event.ID, function (result) {
                // Build the players selection list
                if( "success" === result.status ) {
                    result.data.forEach(function( player ) {
                        $( '#sgc_scorecard_player' ).append('<option value="' 
                                + player.ID + '" ' 
                                + ( selected === player.ID ? 'selected' : '' )
                                + '>' + player.name + '</option>');
                    });
                }
            });
        }
 
        /**
         * Update the player list using selected location
         */
        display_player_list() {
            SGC_Scorecard.load_event();
            SGC_Scorecard.render_player_list();
        }
        
        /**
         * Save te date from the selected event
         */
        save_date() {
            $.getJSON(SGCtxt.url_site + SGCtxt.url_rest + '/event/info/' 
                    + SGC_Scorecard.event.ID, function (result) {
                // Build the players selection list
                if( "success" === result.status ) {
                    $("#sgc_scorecard_timestamp").val(result.data['time']);
                }
            });
        }
        
        close_thickbox() {
            tb_remove();
        }
    }
    
// Load the class after the document is ready
$(document).ready(function () {
    if ( $('#sgc_javascript').val() == 'sgc_scorecard' ) {
        let sgc_scorecard = new SGC_Scorecard();
        sgc_scorecard.display_player_list();
        sgc_scorecard.display_tee_list();

        //Bind to WP Publish event
        $("#publish").click(sgc_scorecard.publish_hook);
        
        $('#sgc_scorecard_event_save').click( sgc_scorecard.close_thickbox );       // Done button for scorecard event
        $('#sgc_scorecard_event_save').click( sgc_scorecard.display_player_list );       // Done button for scorecard event update players
        $('#sgc_scorecard_event_save').click( sgc_scorecard.display_tee_list );       // Done button for scorecard event update tees
        $('#sgc_scorecard_event_save').click( sgc_scorecard.save_date );       // Done button for scorecard event save date
    }
});

})( jQuery );
    
