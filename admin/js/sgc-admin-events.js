/**
 * All the scripts needed the events custom post type
 */

(function ($) {
    'use strict';
    
    class SGC_Event {

        constructor( group_data, group_display, group_summary, team_data) {

            // Init Location Search
            this.select_locations = new SGC_Search_Select( 'sgc_event_manage_locations_selectsearch', 'sgc_selected_locations_summary', SGC_Search_CATEGORIES.locations, true );

            // Init Team Search
            this.select_teams = new SGC_Search_Select( 'sgc_event_manage_teams_selectsearch', 'sgc_selected_teams_summary', SGC_Search_CATEGORIES.teams, true );

            // Information for group creation
            SGC_Event.group_data = group_data;
            SGC_Event.group_display = group_display;
            SGC_Event.group_summary = group_summary;
            SGC_Event.team_data = team_data;
            
            SGC_Event.player_list = [];
            SGC_Event.player_checkedin_list = [];
            SGC_Event.group_list = [];

            SGC_Event.load_group();
            SGC_Event.display_group_summary();
        }

        /**
         * 
         */
        update_tees( ) {
            // Disable tee select
            $( '#sgc_event_tee' ).addClass('sgc-disabled');
            //Clear tee_select_html
            $( '#sgc_event_tee' ).html('<option value="">' + SGCtxt.opt_anytee + '</option>');

            // Get location info
            let location_list = JSON.parse( $('textarea[name=sgc_event_locations]').val() );
            location_list.some(function( item ) {
                if( !item.delete ) {
                    // Fetch the Tee List
                    $.getJSON(SGCtxt.url_site + SGCtxt.url_rest + '/location/tees/' 
                            + item.ID, function (result) {
                        // Build the tee_select_html list
                        result.data.forEach(function( tee ) {
                            $( '#sgc_event_tee' ).append('<option value="' + tee.color 
                                    + '" ' + (tee.isdefault ? 'selected="selected"' : '') + '>' + tee.color 
                                    + ' (' + tee.difficulty + ')' + (tee.isdefault ? '&ast;' : '') 
                                    + '</option>');
                        });
                        // Enable the element
                        $('#sgc_event_tee').removeClass( 'sgc-disabled' );
                    });
                }
            });
        }

        /**
         * 
         */
        static load_group() {
            if ( $(SGC_Event.group_data).text().trim() !== '' ) {
                SGC_Event.group_list = JSON.parse( $(SGC_Event.group_data).text().trim() );
            }
        }

        /**
         * 
         */
        manage_groups () {
            // get the current team
            let team_list = JSON.parse( $('textarea[name=sgc_event_teams]').val() ); 
            
            // Fetch the checked in player list for the event
            $.getJSON(SGCtxt.url_site + SGCtxt.url_rest + '/event/players/' 
                    + $("#post_ID").val(), function ( checkedin_result ) {
                SGC_Event.player_checkedin_list = checkedin_result.data;
            }).done( function ( team_result ){
                SGC_Event.display_group_list( );
            });
            
            // Fetch the player list for the event
            let team_id = team_list[0]['ID'];
            $.getJSON(SGCtxt.url_site + SGCtxt.url_rest + '/team/players/' 
                    + team_id, function ( team_result ) {
                SGC_Event.player_list = team_result.data;
            }).done( function ( team_result ){
                SGC_Event.display_group_list( );
            });
            
            SGC_Event.display_group_list( );
        }

        /**
         * 
         */
        static display_group_list( ) {
            // Clear the grouplist
            $(SGC_Event.group_display).html('');

            // Exit early if we don't have a group
            if ( !Array.isArray(SGC_Event.group_list) || SGC_Event.group_list.length < 1 ) 
            { return 0; }

            // Loop through the group array and build the group dialogs
            SGC_Event.group_list.forEach(function ( group, group_idx ) {
                // Create the group container
                $(SGC_Event.group_display).append('<div class="sgc-event-group" '
                    + 'id="sgc_event_group_' + group_idx + '"'
                    + ' style="display: inline-block"></div>');

                // Don't allow blank names
                if ( group.name == '' ) { group.name = SGCtxt.txt_group + ' #' 
                            + eval( group_idx + ' + 1' ) ; }

                // Add the group Heading
                $('#sgc_event_group_' + group_idx).append('<div>'
                    + '<input type="text" id="sgc_event_group_' + group_idx + '_name" placeholder="' 
                    + SGCtxt.opt_groupname + '" group_id="' + group_idx
                    + '" value="' + group.name + '"/>'
                    + '<a href="" id="sgc_event_group_' + group_idx + '_delete" class="sgc-button sgc-button-delete"'
                    + 'group_id="' 
                    + group_idx + '">' + SGCtxt.txt_delete +'</a>'
                    + '</div>');

                // bind group heading events
                $('#sgc_event_group_' + group_idx + '_delete').click(SGC_Event.delete_group);
                $('#sgc_event_group_' + group_idx + '_name').change(SGC_Event.update_group_name);

                // Add player selection list
                $('#sgc_event_group_' + group_idx).append('<div>'
                            + '<select class="sgc-event-group-playerlist" id="sgc_event_group_' + group_idx + '_player_list">'
                            + '<option value="">' + SGCtxt.opt_selectplayer + '</option>'
                            + '</select>'
                            + '<a href="" id="sgc_event_group_' + group_idx + '_player_add" group_id="' 
                            + group_idx + '" player_select="#sgc_event_group_' + group_idx + '_player_list"'
                            + 'class="sgc-button">'
                            + SGCtxt.txt_add + '</a>' + '</div>');

                // Populate the player selection list
                SGC_Event.player_list.forEach( function( player ) {
                    let checked_in = '';
                    SGC_Event.player_checkedin_list.forEach ( function( checkedin ) {
                        if( player.ID === checkedin.ID && checkedin.checkedin == 'true' ) { 
                            checked_in = ' &#10004;';
                        }
                    });

                    $('#sgc_event_group_' + group_idx + '_player_list').append(
                            '<option value="' + player.ID
                            + '" name="' + player.name
                            + '" URL="' + player.URL
                            + '">' + player.name + '&nbsp;' + checked_in + '</option>');
                });

                // Bind player selection list events
                $('#sgc_event_group_' + group_idx + '_player_add').click(SGC_Event.add_player);

                // Add the group player list body
                $('#sgc_event_group_' + group_idx).append('<div class="sgc-event-group-players"'
                    + 'id="sgc_event_group_' + group_idx + '_players">'
                    + '<ul id="sgc_event_group_' + group_idx + '_player_list"></ul>'
                    + '</div>');


                // Populate the group player list body
                group.players.forEach( function ( player, player_idx ) {
                    $('ul[id=sgc_event_group_' + group_idx + '_player_list]').append(
                            '<li class="sgc-event-group-player-list"><a href="' + player.URL 
                            + '" target="Player">' + player.name + '</a>'
                            + '<small class="sgc-action" style="float: right"><a href="" id="sgc_event_group'
                            + group_idx + '_player' + player_idx + '_delete" class="sgc-button sgc-button-delete" '
                            + 'player_id="' + player_idx + '" group_id="' + group_idx + '">Delete</a></small></li>');

                    // Bind player delete events
                    $('#sgc_event_group' + group_idx + '_player' + player_idx + '_delete').click(SGC_Event.delete_player);
                });
            });
        }

        /**
         * 
         */
        static display_group_summary() {
            // Clear the summary
            $(SGC_Event.group_summary).html('<ul class="sgc-event-group-summary" '
                + 'id="sgc_event_group_summary"></ul>');

            //loop through the list
            SGC_Event.group_list.forEach( function (group, group_idx) {
                // Add the group to the group summary list
                $('#sgc_event_group_summary').append('<li class="sgc-event-group-summary">'
                    + '<span class="sgc-event-group-summary-name">' + group.name + '</span>'
                    + '<small style="float: right"><a href="" id="sgc_event_group_summary_' + group_idx + '_delete" group_id="' 
                + group_idx + '" class="sgc-button sgc-button-delete sgc-action">' + SGCtxt.txt_delete + '</a></small>');

                // Bind player delete events
                $('#sgc_event_group_summary_' + group_idx + '_delete').click(SGC_Event.quick_delete_group);
            });
        }

        /**
         * 
         */
        add_group() {
            // Add a blank group to the array
            SGC_Event.group_list.push({
                'name' : '',
                'players' : []
            });

            SGC_Event.display_group_list();
            return false;
        }

        /**
         * 
         */
        static delete_group( ) {
            let group_idx = $(this).attr('group_id');
            SGC_Event.group_list.splice(group_idx, 1);
            SGC_Event.display_group_list();
            SGC_Event.display_group_summary();
            return false;
        }

        /**
         * 
         */
        static quick_delete_group( ) {
            let group_idx = $(this).attr('group_id');
            SGC_Event.group_list.splice(group_idx, 1);

            // Save the array to the form element
            if (SGC_Event.group_list.length > 0) {
                $(SGC_Event.group_data).text(JSON.stringify(SGC_Event.group_list));
            } else {
                $(SGC_Event.group_data).text('[]');
            }

            SGC_Event.display_group_summary();
            return false;
        }

        /**
         * 
         */
        static update_group_name() {
            let group_idx = $(this).attr('group_id');
            SGC_Event.group_list[group_idx].name = $(this).val()
                    .replace(/ \"/g, " &ldquo;")
                    .replace(/\" /g, "&rdquo; ");
            SGC_Event.display_group_list( );
        }

        /**
         * 
         */
        static add_player() {
            let group_idx = $(this).attr('group_id');
            let player_id = $($(this).attr('player_select')).find('option:selected').val();
            let add_player = true;
            // Exit early on invalid player
            if ( player_id < 0 ) { return false; }

            // Don't add a player already on this list
            SGC_Event.group_list[group_idx].players.forEach( function ( player ) {
                if( player.ID == player_id ) { add_player = false; }
            });

            // find the matching player id and add them to the group
            if( add_player ) {
                SGC_Event.player_list.forEach( function ( player ) {
                    if ( player.ID == player_id ) {
                        SGC_Event.group_list[group_idx].players.push(
                                {'ID': player.ID, 
                                'name': player.name, 
                                'URL': player.URL});
                        SGC_Event.display_group_list();
                        return false;
                    }
                });
            }

            return false;
        }

        /**
         * 
         */
        static delete_player() {
            let group_id = $(this).attr('group_id');
            let player_id = $(this).attr('player_id');

            // Exit early for invalid values
            if ( group_id < 0 || player_id < 0 ) { return false; }

            //remove the player
            SGC_Event.group_list[group_id].players.splice(player_id, 1);
            SGC_Event.display_group_list();
            return false;
        }

        /**
         * 
         */
        save_group_list() {
            // Save the array to the form element
            if (SGC_Event.group_list.length > 0) {
                $(SGC_Event.group_data).text(JSON.stringify(SGC_Event.group_list));
            } else {
                $(SGC_Event.group_data).text('[]');
            }

            // Update the group summary
            SGC_Event.display_group_summary();

            // close the thickbox
            tb_remove();

            return false;
        }
        
        close_thickbox() {
            tb_remove();
        }

    }
    
    // Load the class after the document is ready
    $(document).ready(function () {
        if ( $('#sgc_javascript').val() === 'sgc_event' ) {
            let sgc_event = new SGC_Event(
                '#sgc_event_group_list', '#sgc_event_group_display', '#sgc_event_groups_summary', 'sgc_event_teams');

            // Bind to thickbox save elements
            $('#sgc_event_location_save').click( sgc_event.close_thickbox );       // Done button for event location
            $('#sgc_event_location_save').click( sgc_event.update_tees );       // Update tees on save
            $('#sgc_event_team_save').click( sgc_event.close_thickbox );       // Done button for event team
            
            // Bind to Group elements
            $('#sgc_event_manage_button').click(sgc_event.manage_groups); // Manage Groups button
            $('#sgc_event_group_add').click(sgc_event.add_group);        // Group Add button
            $('#sgc_event_group_save').click(sgc_event.save_group_list); // Save group button
            
            // Configure date and time picker (pickadate)
            $("#sgc_eventdate").pickadate({format: 'mmmm d yyyy'});
            $("#sgc_eventtime").pickatime();
        }
    });
    
})( jQuery );
