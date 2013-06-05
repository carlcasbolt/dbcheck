
/**
 * global variable to store the comparison table
 */
var comp_table;

/**
 * boolean values used to store the current status of a show / hide toggle
 */
var hide_identical = true;
var hide_different = true;
var hide_missing   = true;

/**
 * toggleRows
 * 
 * Change the visibility of a given table from a given tr style, which contains a given td style
 * 
 * @param   string    tr_style  - used to locate a group of rows with a given css style
 * @param   string    td_style  - used to check each row contains a given css style
 * @param   boolean   state     - toggle status, wether to show / hide the table row
 *
 * @return  boolean
 */
function toggleRows(tr_style, td_style, state)
{
	table_rows = comp_table.select(tr_style);

	table_rows.each(function(table_row) {
		if(table_row.select(td_style).size()) {
			if (state) {
				table_row.hide();
			} else {
				table_row.show();
			}
		}
	});
	return ! state;
}

/**
 * toggleLink
 *
 * Toggle the show / hide links for a given id using the given state
 *
 * @param   string    id_part   - part of the element id we want to toggle
 * @param   string    state     - the current state of the show / hide toggle
 */
function toggleLink(id_part, state)
{
	var hide_link = document.getElementById('togglesHide'+id_part);
	var show_link = document.getElementById('togglesShow'+id_part);

	if (state) {
		show_link.hide();
		hide_link.show();
	} else {
		hide_link.hide();
		show_link.show();
	}		
}

/**
 * toggleIdentical
 *
 * Toggle the table rows and links for identical results
 */
function toggleIdentical()
{
	hide_identical = toggleRows('tr.identical', 'td.present', hide_identical);

	toggleLink('Identical', hide_identical);
}

/**
 * toggleDifferent
 *
 * Toggle the table rows and links for different results
 */
function toggleDifferent()
{
	hide_different = toggleRows('tr.different', 'td.present', hide_different);
	hide_missing = hide_different;

	toggleLink('Different', hide_different);
	toggleLink('Missing', hide_missing);
}

/**
 * toggleMissing
 *
 * Toggle the table rows and links for missing results
 */
function toggleMissing()
{
	hide_missing = toggleRows('tr.different', 'td.missing', hide_missing);

	toggleLink('Missing', hide_missing);
}

/**
 * addToggle
 * 
 * Add the onclick even to a given element id
 *
 * @param   string    id       - the id of the element to apply an onclick to
 * @param   string    method   - the method to apply an onclick to
 */
function addToggle(id, method)
{
	document.getElementById(id).onclick = function() {
		method();
		return false;
	}
}

/**
 * initToggles
 *
 * Initialise the toggle buttons
 */
function initToggles()
{
	comp_table = $('comparison_results');

	if (comp_table) {
		addToggle('togglesHideDifferent', toggleDifferent);
		addToggle('togglesShowDifferent', toggleDifferent);

		addToggle('togglesHideIdentical', toggleIdentical);
		addToggle('togglesShowIdentical', toggleIdentical);

		addToggle('togglesHideMissing', toggleMissing);
		addToggle('togglesShowMissing', toggleMissing);
	}
}

/**
 * Add the onload event to initialise the toggles
 */
window.onload = initToggles;

