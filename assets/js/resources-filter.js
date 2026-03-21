/**
 * Faithful Witness — Resource Library Filter
 *
 * Filters the resource grid by:
 *   - Resource type (pill buttons)
 *   - Initiative category (select dropdown)
 *   - Search text (title + excerpt)
 *
 * All filtering is client-side (no AJAX needed).
 * Cards are pre-rendered server-side with data attributes.
 */

( function () {
  'use strict';

  /* ----------------------------------------------------------------
   * Elements
   * ---------------------------------------------------------------- */
  const grid           = document.getElementById( 'resources-grid' );
  const noResults      = document.getElementById( 'resources-no-results' );
  const countEl        = document.getElementById( 'resource-count' );
  const searchEl       = document.getElementById( 'resource-search' );
  const initiativeEl   = document.getElementById( 'initiative-filter' );
  const activeFiltersEl = document.getElementById( 'active-filters' );
  const activeTagsEl   = document.getElementById( 'active-filter-tags' );
  const clearBtn       = document.getElementById( 'clear-filters' );
  const resetAllBtn    = document.getElementById( 'reset-all-filters' );
  const typePills      = document.querySelectorAll( '.filter-pill[data-filter-type="type"]' );
  const issuePills     = document.querySelectorAll( '.filter-pill[data-filter-type="issue_area"]' );
  const audiencePills  = document.querySelectorAll( '.filter-pill[data-filter-type="audience"]' );

  if ( ! grid ) return;

  const cards = Array.from( grid.querySelectorAll( '.resource-card' ) );

  /* ----------------------------------------------------------------
   * State
   * ---------------------------------------------------------------- */
  let activeType       = '';   // resource type slug
  let activeInitiative = '';   // initiative term ID (as string)
  let activeIssueArea  = '';   // issue area slug
  let activeAudience   = '';   // audience slug
  let searchQuery      = '';

  /* ----------------------------------------------------------------
   * Filter function
   * ---------------------------------------------------------------- */
  function applyFilters() {
    const q = searchQuery.toLowerCase().trim();
    let visibleCount = 0;

    cards.forEach( function ( card ) {
      const typeVal     = card.dataset.type || '';
      const initVal     = ( card.dataset.initiative || '' ).split( ',' ).map( s => s.trim() );
      const issueVal    = ( card.dataset.issueArea || '' ).split( ',' ).map( s => s.trim() );
      const audienceVal = ( card.dataset.audience || '' ).split( ',' ).map( s => s.trim() );
      const titleEl    = card.querySelector( '.card__title' );
      const excerptEl  = card.querySelector( '.card__excerpt' );
      const titleText  = titleEl ? titleEl.textContent.toLowerCase() : '';
      const excerptText = excerptEl ? excerptEl.textContent.toLowerCase() : '';

      // Type filter
      const typeMatch = ! activeType || typeVal === activeType;

      // Initiative filter
      const initMatch = ! activeInitiative || initVal.includes( activeInitiative );

      // Issue area filter
      const issueMatch = ! activeIssueArea || issueVal.includes( activeIssueArea );

      // Audience filter
      const audienceMatch = ! activeAudience || audienceVal.includes( activeAudience );

      // Search filter
      const searchMatch = ! q || titleText.includes( q ) || excerptText.includes( q );

      const visible = typeMatch && initMatch && issueMatch && audienceMatch && searchMatch;
      card.style.display = visible ? '' : 'none';
      if ( visible ) visibleCount++;
    } );

    // Show/hide no-results
    if ( noResults ) {
      noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    // Update count
    if ( countEl ) {
      countEl.textContent = visibleCount === 1
        ? 'Showing 1 resource'
        : `Showing ${visibleCount} resource${visibleCount !== 1 ? 's' : ''}`;
    }

    // Update active filters indicator
    updateActiveFiltersBar();
  }

  /* ----------------------------------------------------------------
   * Active filters bar
   * ---------------------------------------------------------------- */
  function updateActiveFiltersBar() {
    if ( ! activeFiltersEl || ! activeTagsEl ) return;

    const hasFilters = activeType || activeInitiative || activeIssueArea || activeAudience || searchQuery;
    activeFiltersEl.style.display = hasFilters ? 'flex' : 'none';

    activeTagsEl.innerHTML = '';

    if ( activeType ) {
      const activePill = document.querySelector( `.filter-pill[data-value="${activeType}"]` );
      const label = activePill ? activePill.textContent.trim() : activeType;
      activeTagsEl.appendChild( makeFilterTag( label, function () {
        activeType = '';
        setActivePill( '' );
        applyFilters();
      } ) );
    }

    if ( activeInitiative && initiativeEl ) {
      const selectedOpt = initiativeEl.options[ initiativeEl.selectedIndex ];
      activeTagsEl.appendChild( makeFilterTag( selectedOpt.text, function () {
        activeInitiative = '';
        initiativeEl.value = '';
        applyFilters();
      } ) );
    }

    if ( activeIssueArea ) {
      const activePill = document.querySelector( `.filter-pill[data-filter-type="issue_area"][data-value="${activeIssueArea}"]` );
      const label = activePill ? activePill.textContent.trim() : activeIssueArea;
      activeTagsEl.appendChild( makeFilterTag( label, function () {
        activeIssueArea = '';
        setActivePillGroup( issuePills, '' );
        applyFilters();
      } ) );
    }

    if ( activeAudience ) {
      const activePill = document.querySelector( `.filter-pill[data-filter-type="audience"][data-value="${activeAudience}"]` );
      const label = activePill ? activePill.textContent.trim() : activeAudience;
      activeTagsEl.appendChild( makeFilterTag( label, function () {
        activeAudience = '';
        setActivePillGroup( audiencePills, '' );
        applyFilters();
      } ) );
    }

    if ( searchQuery ) {
      activeTagsEl.appendChild( makeFilterTag( `"${searchQuery}"`, function () {
        searchQuery = '';
        if ( searchEl ) searchEl.value = '';
        applyFilters();
      } ) );
    }
  }

  function makeFilterTag( label, onRemove ) {
    const span = document.createElement( 'span' );
    span.className = 'active-filter-tag';
    span.style.cssText = 'display:inline-flex;align-items:center;gap:.4rem;background:rgba(27,79,114,.1);color:var(--color-primary);padding:.2rem .75rem;border-radius:9999px;font-size:.8rem;font-weight:600;margin-right:.5rem;';
    span.innerHTML = `${escHtml( label )} <button aria-label="Remove filter" style="background:none;border:none;cursor:pointer;font-size:1rem;line-height:1;color:inherit;padding:0;">×</button>`;
    span.querySelector( 'button' ).addEventListener( 'click', onRemove );
    return span;
  }

  /* ----------------------------------------------------------------
   * Type filter pills
   * ---------------------------------------------------------------- */
  typePills.forEach( function ( pill ) {
    pill.addEventListener( 'click', function () {
      const value = pill.dataset.value || '';
      activeType = value;
      setActivePillGroup( typePills, value );
      applyFilters();
    } );
  } );

  issuePills.forEach( function ( pill ) {
    pill.addEventListener( 'click', function () {
      const value = pill.dataset.value || '';
      activeIssueArea = value;
      setActivePillGroup( issuePills, value );
      applyFilters();
    } );
  } );

  audiencePills.forEach( function ( pill ) {
    pill.addEventListener( 'click', function () {
      const value = pill.dataset.value || '';
      activeAudience = value;
      setActivePillGroup( audiencePills, value );
      applyFilters();
    } );
  } );

  function setActivePillGroup( pills, value ) {
    pills.forEach( function ( p ) {
      const isActive = ( p.dataset.value || '' ) === value;
      p.classList.toggle( 'active', isActive );
      p.setAttribute( 'aria-pressed', isActive ? 'true' : 'false' );
    } );
  }

  // Keep backward-compat alias used by URL hash reader
  function setActivePill( value ) {
    setActivePillGroup( typePills, value );
  }

  /* ----------------------------------------------------------------
   * Initiative filter
   * ---------------------------------------------------------------- */
  if ( initiativeEl ) {
    initiativeEl.addEventListener( 'change', function () {
      activeInitiative = initiativeEl.value;
      applyFilters();
    } );
  }

  /* ----------------------------------------------------------------
   * Search
   * ---------------------------------------------------------------- */
  if ( searchEl ) {
    let debounceTimer;
    searchEl.addEventListener( 'input', function () {
      clearTimeout( debounceTimer );
      debounceTimer = setTimeout( function () {
        searchQuery = searchEl.value;
        applyFilters();
      }, 200 );
    } );
  }

  /* ----------------------------------------------------------------
   * Clear / Reset
   * ---------------------------------------------------------------- */
  function resetAll() {
    activeType       = '';
    activeInitiative = '';
    activeIssueArea  = '';
    activeAudience   = '';
    searchQuery      = '';
    setActivePillGroup( typePills, '' );
    setActivePillGroup( issuePills, '' );
    setActivePillGroup( audiencePills, '' );
    if ( initiativeEl ) initiativeEl.value = '';
    if ( searchEl )     searchEl.value = '';
    applyFilters();
  }

  if ( clearBtn )    clearBtn.addEventListener( 'click', resetAll );
  if ( resetAllBtn ) resetAllBtn.addEventListener( 'click', resetAll );

  /* ----------------------------------------------------------------
   * URL hash: allow deep linking to a filtered state
   * e.g. /resources/#type=graphic or /resources/#initiative=123
   * ---------------------------------------------------------------- */
  function readUrlHash() {
    const hash = window.location.hash.replace( '#', '' );
    if ( ! hash ) return;
    const params = new URLSearchParams( hash );
    if ( params.has( 'type' ) ) {
      activeType = params.get( 'type' );
      setActivePill( activeType );
    }
    if ( params.has( 'initiative' ) ) {
      activeInitiative = params.get( 'initiative' );
      if ( initiativeEl ) initiativeEl.value = activeInitiative;
    }
    if ( activeType || activeInitiative ) applyFilters();
  }
  readUrlHash();

  /* ----------------------------------------------------------------
   * Escape helper
   * ---------------------------------------------------------------- */
  function escHtml( str ) {
    const div = document.createElement( 'div' );
    div.appendChild( document.createTextNode( str ) );
    return div.innerHTML;
  }

} )();
