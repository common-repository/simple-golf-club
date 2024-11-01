/**
 * Location custom post type javascript class
 */

(function ($) {
    'use strict';
    
    class SGC_Location {

        constructor( tee_data, tee_summary ) {
            SGC_Location.tee_data = tee_data;
            SGC_Location.tee_summary = tee_summary;

            SGC_Location.tees = [];

            SGC_Location.load_tees();
        }
        
        /**
         * 
         */
        static new_tee() {
            // Add a blank tee to the array
            SGC_Location.tees.push({
                'color': '',
                'difficulty': '',
                'isdefault': false,
                'average_par': '',
                'average_rating': '',
                'average_slope': '',
                'par': [],
                'rating' : [],
                'length': []
            });
        }

        /**
         * 
         */
        static load_tees() {
            if ( $(SGC_Location.tee_data).text().trim() !== '' ) {
                SGC_Location.tees = JSON.parse($(SGC_Location.tee_data).text());
            }

            SGC_Location.display_tee_summary();
        }

        /**
         * 
         */
        static save_tees_data() {        
            // Save the array to the form element
            if (SGC_Location.tees.length > 0) {
                $(SGC_Location.tee_data).text(JSON.stringify(SGC_Location.tees));
            } else {
                $(SGC_Location.tee_data).text('[]');
            }

            SGC_Location.display_tee_summary();
        }

        /**
         * 
         */
        static display_tee_summary() {
            // clear contianer
            $(SGC_Location.tee_summary).html('<ul class="sgc-location-tee" '
                + 'id="sgc_location_teelist"></ul>');

            // loop through the list and add the existing tees
            SGC_Location.tees.forEach( function( tee, tee_idx) {
                if ( tee.color === '' ) 
                { tee.color = SGCtxt.txt_tee + ' #' + eval( tee_idx + ' + 1' ); }
                let tee_difficulty = '';
                if ( tee.difficulty !== '' ) { tee_difficulty = ' (' + tee.difficulty + ')'; }

                // Add the tee to the tee summary list
                $('#sgc_location_teelist').append('<li class="sgc-location-tee">'
                    + '<a href="#TB_inline?width=600&height=550&inlineId=sgc_location_tee"' 
                    + ' class="thickbox" id="sgc_location_tee_' + tee_idx + '_edit"'
                    + ' tee_id= "' + tee_idx + '" name="' + SGCtxt.txt_edittee + '">' 
                    + tee.color + tee_difficulty + '</a>'
                    + '<small> ' + ((tee.isdefault) ? '&ast;' : '' ) + '</small>'
                    + '<small class="sgc-action" style="float: right"><a href="" id="sgc_location_tee_' + tee_idx + '_delete" tee_id="' 
                + tee_idx + '" class="sgc-button sgc-button-delete">' + SGCtxt.txt_delete + '</a></small>');

                // Bind events
                $('#sgc_location_tee_' + tee_idx + '_edit' ).click(SGC_Location.edit_tee);
                $('#sgc_location_tee_' + tee_idx + '_delete' ).click(SGC_Location.quick_delete_tee);
            });
        }

        /**
         * 
         */
        add_tee() {
            SGC_Location.new_tee();
            SGC_Location.clear_tee_form();
            $('#sgc_location_tee_index').val( eval( SGC_Location.tees.length + ' - 1' ) );
            SGC_Location.display_tee_summary();
        }

        /**
         * 
         */
        static clear_tee_form() {
            //clear tee color and difficulty
            $('#sgc_location_tee_idx').val('-1');
            $('#sgc_location_tee_color').val('');
            $('#sgc_location_tee_difficulty').val('');
            $('#sgc_location_tee_avgpar').val('');
            $('#sgc_location_tee_avgrating').val('');
            $('#sgc_location_tee_isdefault').prop('checked', false);
            $('#sgc_location_tee_avgslope').val('');

            // clear the tee par, rank, and lenght fields
            $(document).find(':input').each(function () {
                if ( $(this).attr('name') === 'sgc_location_tee_par'
                        || $(this).attr('name') === 'sgc_location_tee_rating'
                        || $(this).attr('name') === 'sgc_location_tee_length' ) {
                    $(this).val('');
                }
            });
        }

        /**
         * 
         */
        static edit_tee() {
            let tee_id = $(this).attr('tee_id');

            // Set the tee id
            $('#sgc_location_tee_index').val( tee_id );

            // set the tee color, difficulty, and average info
            $('#sgc_location_tee_color').val( SGC_Location.tees[tee_id].color );
            $('#sgc_location_tee_difficulty').val( SGC_Location.tees[tee_id].difficulty );
            $('#sgc_location_tee_avgpar').val( SGC_Location.tees[tee_id].average_par );
            $('#sgc_location_tee_avgrating').val( SGC_Location.tees[tee_id].average_rating );
            $('#sgc_location_tee_isdefault').prop('checked', SGC_Location.tees[tee_id].isdefault);
            $('#sgc_location_tee_avgslope').val( SGC_Location.tees[tee_id].average_slope );

            // load tee par
            let i = 0;
            $(document).find(':input').each(function () {
                if ($(this).attr('name') === 'sgc_location_tee_par') {
                    $(this).val(SGC_Location.tees[tee_id].par[i]);
                    i++;
                }
            });

            // load tee rating
            i = 0;
            $(document).find(':input').each(function () {
                if ($(this).attr('name') === 'sgc_location_tee_rating') {
                    $(this).val(SGC_Location.tees[tee_id].rating[i]);
                    i++;
                }
            });

            // load tee length
            i = 0;
            $(document).find(':input').each(function () {
                if ($(this).attr('name') === 'sgc_location_tee_length') {
                    $(this).val(SGC_Location.tees[tee_id].length[i]);
                    i++;
                }
            });
        }

        /**
         * 
         */
        save_tee() {
            let tee_idx = $('#sgc_location_tee_index').val();
            let tee_color = $('#sgc_location_tee_color').val();
            if (tee_color === '' ) 
            { tee_color = SGCtxt.txt_tee + ' #' + eval( tee_idx + ' + 1' ); }
            let tee_difficulty = $('#sgc_location_tee_difficulty').val();
            let tee_avgpar = $('#sgc_location_tee_avgpar').val();
            let tee_avgrating = $('#sgc_location_tee_avgrating').val();
            let tee_isdeafault = $('#sgc_location_tee_isdefault').is(":checked"); 
            let tee_avgslope = $('#sgc_location_tee_avgslope').val();

            //build the tee par array
            let tee_pars = [];
            $(document).find(':input').each(function () {
                if ($(this).attr('name') === 'sgc_location_tee_par') {
                    tee_pars.push($(this).val());
                }
            });

            //build the tee rating array
            let tee_ratings = [];
            $(document).find(':input').each(function () {
                if ($(this).attr('name') === 'sgc_location_tee_rating') {
                    tee_ratings.push($(this).val());
                }
            });

            //build the tee length array
            let tee_lengths = [];
            $(document).find(':input').each(function () {
                if ($(this).attr('name') === 'sgc_location_tee_length') {
                    tee_lengths.push($(this).val());
                }
            });

            // update the default tee status
            SGC_Location.update_default_tee();
            
            // Update the existing tee
            SGC_Location.tees[tee_idx].color = tee_color;
            SGC_Location.tees[tee_idx].difficulty = tee_difficulty;
            SGC_Location.tees[tee_idx].average_par = tee_avgpar;
            SGC_Location.tees[tee_idx].average_rating = tee_avgrating;
            SGC_Location.tees[tee_idx].isdefault = tee_isdeafault;
            SGC_Location.tees[tee_idx].average_slope = tee_avgslope;
            SGC_Location.tees[tee_idx].par = tee_pars;
            SGC_Location.tees[tee_idx].rating = tee_ratings;
            SGC_Location.tees[tee_idx].length = tee_lengths;

            // Save the course array to the form element
            SGC_Location.save_tees_data();

            // Update the summary
            SGC_Location.display_tee_summary();

            // close the thickbox
            tb_remove();

            return false;
        }

        /**
         * 
         */
        delete_tee() {
            SGC_Location.tees.splice( $('#sgc_location_tee_index').val(), 1 );
            SGC_Location.save_tees_data();
            SGC_Location.display_tee_summary();

            // close the thickbox
            tb_remove();

            return false;
        }

        /**
         * 
         */
        static quick_delete_tee() {
            SGC_Location.tees.splice( $(this).attr('tee_id'), 1 );
            SGC_Location.save_tees_data();
            SGC_Location.display_tee_summary();

            return false;
        }
        
        /**
         * 
         */
        copy_tee() {
            SGC_Location.new_tee();
            // Change the tee index to the newly created tee entry
            $('#sgc_location_tee_index').val( eval( SGC_Location.tees.length + ' - 1' ) );
            // Update the tee name
            $('#sgc_location_tee_color').val( $('#sgc_location_tee_color').val() + ' Copy');
            // Update the summary
            SGC_Location.display_tee_summary();
            return false;
        }
        
        /**
         * 
         */
        static update_default_tee() {
            let this_tee_idx = $('#sgc_location_tee_index').val();
            
            if ( $('#sgc_location_tee_isdefault').is(":checked") == true ) {
                // loop through and set isdefault to false for all other tees
                SGC_Location.tees.forEach( function( tee, tee_idx) {
                    if ( this_tee_idx != tee_idx ){
                        tee.isdefault = false;
                    }
                });
            }
            return false;
        }
    }

    // Load the class after the document is ready
    $(document).ready(function () {
        if ( $('#sgc_javascript').val() == 'sgc_location' ) {
            let sgc_location = new SGC_Location(
                '#sgc_location_tees', '#sgc_location_tee_summary');

            // Bind to elements
            $('#sgc_location_tee_add').click(sgc_location.add_tee);         // Add Tee button
            $('#sgc_location_tee_delete').click(sgc_location.delete_tee);   // Delete Tee button
            $('#sgc_location_tee_save').click(sgc_location.save_tee);       // Save Tee button
            $('#sgc_location_tee_copy').click(sgc_location.copy_tee);       // Copy Tee button
        }
    });
    
})( jQuery );
