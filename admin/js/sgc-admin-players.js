/**
 * Players custom post type javascript class
 */

(function ($) {
    'use strict';

    /***************************************************************************
     * CLASS    : SGC_Player
     *            Main SGC Player class
     * REQUIRES : js/sgc-search/sgc-admin-select.js
     **************************************************************************/
    class SGC_Player {
    
        constructor ( ) {
            this.team_list = [];

            // Init Team Search
            this.select_teams = new SGC_Search_Select( 'sgc_player_manage_teams_selectsearch', 'sgc_selected_teams_summary', SGC_Search_CATEGORIES.teams );
            
        }
        
        save_team () {
            tb_remove();
        }
    } // END : SGC_Player

    // Load the class after the document is ready
    $(document).ready(function () {
        if ( $('#sgc_javascript').val() === 'sgc_player' ) {
            let sgc_player = new SGC_Player();
            $('#sgc_player_manage_teams_selectsearch #sgc_player_team_save').click( sgc_player.save_team );       // Done button for save team list
        }
    });

})( jQuery );