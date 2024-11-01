/**
 * Teams custom post type javascript class
 */

(function ($) {
    'use strict';

    /***************************************************************************
     * CLASS    : SGC_Team
     *            Main SGC Team class
     * REQUIRES : js/sgc-search/sgc-admin-select.js
     **************************************************************************/
    class SGC_Team {
    
        constructor ( ) {
            this.player_list = [];

            // Init Player Search
            this.select_players = new SGC_Search_Select( 'sgc_team_manage_players_selectsearch', 'sgc_selected_players_summary', SGC_Search_CATEGORIES.players );
        }
        
        save_player () {
            tb_remove();
        }
    } // END : SGC_Player

    // Load the class after the document is ready
    $(document).ready(function () {
        if ( $('#sgc_javascript').val() === 'sgc_team' ) {
            let sgc_team = new SGC_Team();
            $('#sgc_team_manage_players_selectsearch #sgc_team_player_save').click( sgc_team.save_player );       // Done button for player select list
        }
    });

})( jQuery );