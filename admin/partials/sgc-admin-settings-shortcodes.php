<?php
/**
 * Provide general settings and options for SGC configuration
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://gitlab.com/mlinton/
 * @since      1.0.0
 *
 * @package    Simplegolfclub
 * @subpackage Simplegolfclub/admin/partials
 */

?>

<div class="sgc-settings shortcodes">

    <ul class="index">
        <li class="heading"><a href="#sc_events">Events</a>
            <ul>
                <li><a href="#sgc_event_info">[sgc_event_info]</a></li>
                <li><a href="#sgc_event_checkin">[sgc_event_checkin]</a></li>
                <li><a href="#sgc_event_groups">[sgc_event_groups]</a></li>
                <li><a href="#sgc_event_players">[sgc_event_players]</a></li>
                <li><a href="#sgc_event_teams">[sgc_event_teams]</a></li>
                <li><a href="#sgc_event_tees">[sgc_event_tees]</a></li>
            </ul>
        </li>
        <li class="heading"><a href="#sc_players">Players</a>
            <ul>
                <li><a href="#sgc_player_info">[sgc_player_info]</a></li>
                <li><a href="#sgc_player_teams">[sgc_player_teams]</a></li>
            </ul>
        </li>
        <li class="heading"><a href="#sc_teams">Teams</a>
            <ul>
                <li><a href="#sgc_team_players">[sgc_team_events]</a></li>
                <li><a href="#sgc_team_players">[sgc_team_players]</a></li>
            </ul>
        </li>
        <li class="heading"><a href="#sc_locations">Locations</a>
            <ul>
                <li><a href="#sgc_location_events">[sgc_location_events]</a></li>
                <li><a href="#sgc_location_tees">[sgc_location_tees]</a></li>
            </ul>
        </li>
        <li class="heading"><a href="#sc_scorecards">Scorecards</a>
            <ul>
                <li><a href="#sgc_scorecard_info">[sgc_scorecard_info]</a></li>
            </ul>
        </li>
    </ul>

    <!-- EVENTS ############################################################################# -->
    <h2 id="sc_events">Events</h2>
    
    <div class="shortcode" id="sgc_event_info">
        <h3>[sgc_event_info]</h3>
        <div class="content">
            <p>Fetches information for a single Event</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_event_info]</td>
                    <td>Will try to use the current post ID to fetch Event info</td>
                </tr>
                <tr>
                    <td>(event_id)</td>
                    <td>Integer (Post ID)</td>
                    <td>[sgc_event_info <b>event_id=&#34;1234&#34;</b>]</td>
                    <td>Will return Event info based off of the Event ID</td>
                </tr>
                <tr>
                    <td>(event_name)</td>
                    <td>String (Post Title)</td>
                    <td>[sgc_event_info <b>event_name=&#34;The Event&#34;</b>]</td>
                    <td>Will return Event info based off of the Event Name (Post Title) </td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <div class="shortcode" id="sgc_event_checkin">
        <h3>[sgc_event_checkin]</h3>
        <div class="content">
            <p>Fetches a list of Players for a single Event and allows them to view and update their checkedin status</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_event_checkin]</td>
                    <td>Will try to use the current post ID to fetch a list of Players</td>
                </tr>
                <tr>
                    <td>(event_id)</td>
                    <td>Integer (Post ID)</td>
                    <td>[sgc_event_checkin <b>event_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a list of Players based off of the Event ID</td>
                </tr>
                <tr>
                    <td>(event_name)</td>
                    <td>String (Post Title)</td>
                    <td>[sgc_event_checkin <b>event_name=&#34;The Event&#34;</b>]</td>
                    <td>Will return a list of Players based off of the Event Name (Post Title) </td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <div class="shortcode" id="sgc_event_groups">
        <h3>[sgc_event_groups]</h3>
        <div class="content">
            <p>Will return a list of Groups for a single Event</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_event_groups]</td>
                    <td>Will try to use the current post ID to fetch a list of Groups</td>
                </tr>
                <tr>
                    <td>event_id</td>
                    <td>Integer (Post ID)</td>
                    <td>[sgc_event_groups <b>event_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a list of Groups based off of the Events&#39;s post ID</td>
                </tr>
                <tr>
                    <td>event_name</td>
                    <td>String (Post Title)</td>
                    <td>[sgc_event_groups <b>event_name=&#34;1234&#34;</b>]</td>
                    <td>Will return a list of Groups based off of the Events&#39;s name (Post Title)</td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <div class="shortcode" id="sgc_event_players">
        <h3>[sgc_event_players]</h3>
        <div class="content">
            <p>Fetches a list of players for a single Event.</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_event_players]</td>
                    <td>Will try to use the current post ID to search for a list of Players</td>
                </tr>
                <tr>
                    <td>event_id</td>
                    <td>Integer (Post ID)</td>
                    <td>[sgc_event_players <b>event_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a list of all Players on a Team based off of the Event post ID</td>
                </tr>
                <tr>
                    <td>event_name</td>
                    <td>String (Post Title)</td>
                    <td>[sgc_event_players <b>event_name=&#34;Golfers&#34;</b>]</td>
                    <td>Will return a list of all Players on a team based off of the Team&#39;s Event name (Post Title)</td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <div class="shortcode" id="sgc_event_teams">
        <h3>[sgc_event_teams]</h3>
        <div class="content">
            <p>Fetches a list of Teams for a single Event</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_event_teams]</td>
                    <td>Will try to use the current post ID to fetch the list of Teams</td>
                </tr>
                <tr>
                    <td>event_id</td>
                    <td>Integer (Post ID)</td>
                    <td>[sgc_event_teams <b>event_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a list of Teams based off of the Events&#39;s post ID</td>
                </tr>
                <tr>
                    <td>event_name</td>
                    <td>String (Post Title)</td>
                    <td>[sgc_event_teams <b>event_name=&#34;Golf Course&#34;</b>]</td>
                    <td>Will return a list of Teams based off of the Event&#39;s name (Post Title)</td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <div class="shortcode" id="sgc_event_tees">
        <h3>[sgc_event_tees]</h3>
        <div class="content">
            <p>Fetches a list of Tees for a single Event</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_event_tees]</td>
                    <td>Will try to use the current post ID to fetch the list of Tees</td>
                </tr>
                <tr>
                    <td>event_id</td>
                    <td>Integer (Post ID)</td>
                    <td>[sgc_event_tees <b>event_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a list of Tees based off of the Events&#39;s post ID</td>
                </tr>
                <tr>
                    <td>event_name</td>
                    <td>String (Post Title)</td>
                    <td>[sgc_event_tees <b>Event_name=&#34;Golf Course&#34;</b>]</td>
                    <td>Will return a list of Tees based off of the Event&#39;s name (Post Title)</td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <!-- PLAYERS ############################################################################# -->
    <h2 id="sc_players">Players</h2>


    <div class="shortcode" id="sgc_player_info">
        <h3>[sgc_player_info]</h3>
        <div class="content">
            <p>Fetches Player information
            <br>If &#34;Display Personal Info.&#34; is unchecked in SGC&#39;s settings, personal info fields will be blank</p>

            <table class="form-table">
            <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_player_info]</td>
                    <td>Will try to use the current post ID to fetch a single Player&#39;s information</td>
                </tr>
                <tr>
                    <td>player_id</td>
                    <td>(Post ID)</td>
                    <td>[sgc_player_info <b>player_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a single Player&#39;s information based off of the Player&#39;s ID (Post ID)</td>
                </tr>
                <tr>
                    <td>player_name</td>
                    <td>(Post Title)</td>
                    <td>[sgc_player_info <b>player_name=&#34;John Doe&#34;</b>]</td>
                    <td>Will return a single Player&#39;s information based off of the Player&#39;s Name (Post Title)</td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <div class="shortcode" id="sgc_player_teams">
        <h3>[sgc_player_teams]</h3>
        <div class="content">
            <p>Fetches a list of Teams that the Player belongs to</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_player_teams]</td>
                    <td>Will try to use the current post ID to fetch the list of Teams they are on</td>
                </tr>
                <tr>
                    <td>player_id</td>
                    <td>(Post ID)</td>
                    <td>[sgc_player_teams <b>player_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a list of Teams the player is on based off of the Player&#39;s ID (Post ID)</td>
                </tr>
                <tr>
                    <td>player_name</td>
                    <td>(Post Title)</td>
                    <td>[sgc_player_teams <b>player_name=&#34;John Doe&#34;</b></td>
                    <td>Will return a list of Teams the player is on based off of the Player&#39;s Name (Post Title)</td>
                </tr>
                <tr>
                    <td>start_date & end_date</td>
                    <td>(date)</td>
                    <td>[sgc_player_teams <b>start_date=&#34;May 1 2024&#34; end_date=&#34;May 20 2024&#34;</b>]</td>
                    <td>When returning multiple results, limit to the date range (Inclusive)</td>
                </tr>
                <tr>
                    <td>sort</td>
                    <td>String</td>
                    <td>[sgc_player_teams <b>sort=&#34;desc&#34;</b>]</td>
                    <td>Sort multiple results by date (desc = Descending : asc = Ascending)</td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <!-- TEAMS ############################################################################# -->
    <h2 id="sc_teams">Teams</h2>
    

    <div class="shortcode" id="sgc_team_players">
        <h3>[sgc_team_players]</h3>
        <div class="content">
            <p>Fetches a list of Team Players</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_team_players]</td>
                    <td>Will try to use the current post ID to fetch the list of Players</td>
                </tr>
                <tr>
                    <td>team_id</td>
                    <td>(Post ID)</td>
                    <td>[sgc_team_players <b>team_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a list of Players based off of the Teams&#39;s ID (Post ID)</td>
                </tr>
                <tr>
                    <td>team_name</td>
                    <td>(Post Title)</td>
                    <td>[sgc_team_players <b>team_name=&#34;John Doe&#34;</b>]</td>
                    <td>Will return a list of Events based off of the Team&#39;s Name (Post Title)</td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <div class="shortcode" id="sgc_team_events">
        <h3>[sgc_team_events]</h3>
        <div class="content">
            <p>Fetches a list of Team Events</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_team_events]</td>
                    <td>Will try to use the current post ID to fetch the list of Events</td>
                </tr>
                <tr>
                    <td>team_id</td>
                    <td>(Post ID)</td>
                    <td>[sgc_team_events <b>team_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a list of Events based off of the Teams&#39;s ID (Post ID)</td>
                </tr>
                <tr>
                    <td>team_name</td>
                    <td>(Post Title)</td>
                    <td>[sgc_team_events <b>team_name=&#34;John Doe&#34;</b>]</td>
                    <td>Will return a list of Events based off of the Team&#39;s Name (Post Title)</td>
                </tr>
                <tr>
                    <td>start_date & end_date</td>
                    <td>(date)</td>
                    <td>[sgc_team_events <b>start_date=&#34;May 1 2024&#34; end_date=&#34;May 20 2024&#34;</b>]</td>
                    <td>When returning multiple results, limit to the date range (Inclusive)</td>
                </tr>
                <tr>
                    <td>sort</td>
                    <td>String</td>
                    <td>[sgc_team_events <b>sort=&#34;desc&#34;</b>]</td>
                    <td>Sort multiple results by date (desc = Descending : asc = Ascending)</td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <!-- LOCATION ############################################################################# -->
    <h2 id="sc_locations">Locations</h2>


    <div class="shortcode" id="sgc_location_events">
        <h3>[sgc_location_events]</h3>
        <div class="content">
            <p>Fetches a list of Events for a single Location</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_location_events]</td>
                    <td>Will try to use the current Location post ID to fetch the list of Events</td>
                </tr>
                <tr>
                    <td>location_id</td>
                    <td>Integer (Post ID)</td>
                    <td>[sgc_location_events <b>location_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a list of Events based off of the Location&#39;s post ID</td>
                </tr>
                <tr>
                    <td>location_name</td>
                    <td>String (Post Title)</td>
                    <td>[sgc_location_events <b>location_name=&#34;Golf Course&#34;</b>]</td>
                    <td>Will return a list of Events based off of the Location&#39;s name (Post Title)</td>
                </tr>
                <tr>
                    <td>start_date & end_date</td>
                    <td>(date)</td>
                    <td>[sgc_location_events <b>start_date=&#34;May 1 2024&#34; end_date=&#34;May 20 2024&#34;</b>]</td>
                    <td>When returning multiple results, limit to the date range (Inclusive)</td>
                </tr>
                <tr>
                    <td>sort</td>
                    <td>String</td>
                    <td>[sgc_location_events <b>sort=&#34;desc&#34;</b>]</td>
                    <td>Sort multiple results by date (desc = Descending : asc = Ascending)</td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <div class="shortcode" id="sgc_location_tees">
        <h3>[sgc_location_tees]</h3>
        <div class="content">
            <p>Fetches a list of Tees for a single Location</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_location_tees]</td>
                    <td>Will try to use the current Location post ID to fetch the list of Tees</td>
                </tr>
                <tr>
                    <td>location_id</td>
                    <td>Integer (Post ID)</td>
                    <td>[sgc_location_tees <b>location_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a list of Tees based off of the Location&#39;s post ID</td>
                </tr>
                <tr>
                    <td>location_name</td>
                    <td>String (Post Title)</td>
                    <td>[sgc_location_tees <b>location_name=&#34;Golf Course&#34;</b>]</td>
                    <td>Will return a list of Tees based off of the Location&#39;s name (Post Title)</td>
                </tr>
            </table>
        </div>
        <hr>
    </div>


    <!-- SCORECARD ############################################################################# -->
    <h2 id="sc_scorecards">Scorecards</h2>


    <div class="shortcode" id="sgc_scorecard_info">
        <h3>[sgc_scorecard_info]</h3>
        <div class="content">
            <p>Fetches a single Scorecard</p>

            <table class="form-table">
                <tr>
                    <th>Option</th>
                    <th>Value</th>
                    <th>Example</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>(None)</td>
                    <td>(None)</td>
                    <td>[sgc_scorecard_info]</td>
                    <td>Will try to use the current Scorecard post ID to fetch a single Scorecard</td>
                </tr>
                <tr>
                    <td>scorecard_id</td>
                    <td>Integer (Post ID)</td>
                    <td>[sgc_scorecard_info <b>scorecard_id=&#34;1234&#34;</b>]</td>
                    <td>Will return a single Scorecard based off of the Scorecard&#39;s post ID</td>
                </tr>
                <tr>
                    <td>scorecard_name</td>
                    <td>String (Post Title)</td>
                    <td>[sgc_scorecard_info <b>scorecard_name=&#34;Golf Course&#34;</b>]</td>
                    <td>Will return a single Scorecard based off of the Scorecard&#39;s name (Post Title)</td>
                </tr>
            </table>
        </div>
        <hr>
    </div>



</div>