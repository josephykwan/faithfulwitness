/**
 * Faithful Witness — Organizing Groups Map
 *
 * Requires: Leaflet.js + Leaflet.markercluster
 * Data:      fwMapData (localized via wp_localize_script)
 *
 * Features:
 * - Cluster markers for dense areas
 * - Click marker → show popup + highlight sidebar card
 * - Sidebar search by city/state name
 * - State dropdown filter
 * - Animated sidebar list items
 */

( function () {
  'use strict';

  /* ----------------------------------------------------------------
   * Guard: data + Leaflet must be available
   * ---------------------------------------------------------------- */
  if ( typeof fwMapData === 'undefined' || typeof L === 'undefined' ) return;

  const groups      = fwMapData.groups || [];
  const mapEl       = document.getElementById( 'fw-map' );
  const listEl      = document.getElementById( 'map-group-list' );
  const searchEl    = document.getElementById( 'map-search' );
  const stateFilter = document.getElementById( 'map-state-filter' );
  const countEl     = document.getElementById( 'map-group-count' );

  if ( ! mapEl ) return;

  /* ----------------------------------------------------------------
   * State
   * ---------------------------------------------------------------- */
  let currentSearch    = '';
  let currentState     = '';
  let activeMarkerId   = null;

  /* ----------------------------------------------------------------
   * Build map
   * ---------------------------------------------------------------- */
  const map = L.map( mapEl, {
    center: [ 39.5, -98.35 ], // Center of the US
    zoom: 4,
    zoomControl: true,
    scrollWheelZoom: false,
  } );

  // Enable scroll wheel zoom only when user interacts
  mapEl.addEventListener( 'click', function () {
    map.scrollWheelZoom.enable();
  } );
  mapEl.addEventListener( 'mouseleave', function () {
    map.scrollWheelZoom.disable();
  } );

  L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 19,
  } ).addTo( map );

  /* ----------------------------------------------------------------
   * Custom marker icon
   * ---------------------------------------------------------------- */
  function makeIcon( isActive ) {
    return L.divIcon( {
      className: 'fw-map-marker' + ( isActive ? ' fw-map-marker--active' : '' ),
      html: `<div class="fw-map-marker__pin"></div>`,
      iconSize: [ 28, 36 ],
      iconAnchor: [ 14, 36 ],
      popupAnchor: [ 0, -36 ],
    } );
  }

  /* ----------------------------------------------------------------
   * Marker cluster group
   * ---------------------------------------------------------------- */
  const clusterGroup = L.markerClusterGroup( {
    maxClusterRadius: 50,
    showCoverageOnHover: false,
    iconCreateFunction: function ( cluster ) {
      const count = cluster.getChildCount();
      return L.divIcon( {
        html: `<div class="fw-cluster">${count}</div>`,
        className: 'fw-cluster-icon',
        iconSize: [ 40, 40 ],
      } );
    },
  } );

  /* ----------------------------------------------------------------
   * Build markers map { id → marker }
   * ---------------------------------------------------------------- */
  const markerMap = {};

  groups.forEach( function ( group ) {
    const marker = L.marker( [ group.lat, group.lng ], { icon: makeIcon( false ) } );

    marker.bindPopup( buildPopup( group ), {
      maxWidth: 280,
      className: 'fw-map-popup',
    } );

    marker.on( 'click', function () {
      setActiveGroup( group.id );
    } );

    markerMap[ group.id ] = marker;
    clusterGroup.addLayer( marker );
  } );

  map.addLayer( clusterGroup );

  /* ----------------------------------------------------------------
   * Build popup HTML
   * ---------------------------------------------------------------- */
  function buildPopup( group ) {
    const location = [ group.city, group.state ].filter( Boolean ).join( ', ' );
    let html = `<div class="fw-popup">`;
    if ( group.thumbnail ) {
      html += `<img src="${escAttr( group.thumbnail )}" alt="" class="fw-popup__thumb" loading="lazy">`;
    }
    html += `<h3 class="fw-popup__title"><a href="${escAttr( group.url )}">${esc( group.title )}</a></h3>`;
    if ( location ) html += `<p class="fw-popup__location">📍 ${esc( location )}</p>`;
    if ( group.excerpt ) html += `<p class="fw-popup__excerpt">${esc( group.excerpt )}</p>`;
    if ( group.email )   html += `<a href="mailto:${escAttr( group.email )}" class="fw-popup__contact">✉ ${esc( group.email )}</a>`;
    if ( group.phone )   html += `<a href="tel:${escAttr( group.phone.replace( /[^0-9+]/g, '' ) )}" class="fw-popup__contact">📞 ${esc( group.phone )}</a>`;
    html += `<a href="${escAttr( group.url )}" class="fw-popup__link">Learn More →</a>`;
    html += `</div>`;
    return html;
  }

  /* ----------------------------------------------------------------
   * Build sidebar list
   * ---------------------------------------------------------------- */
  function buildSidebarList( filteredGroups ) {
    if ( ! listEl ) return;
    listEl.innerHTML = '';

    if ( filteredGroups.length === 0 ) {
      listEl.innerHTML = '<li class="map-group-item map-group-item--empty">No groups match your search.</li>';
      if ( countEl ) countEl.textContent = '0 organizing groups';
      return;
    }

    if ( countEl ) {
      countEl.textContent = filteredGroups.length === 1
        ? '1 organizing group'
        : `${filteredGroups.length} organizing groups`;
    }

    filteredGroups.forEach( function ( group ) {
      const location = [ group.city, group.state ].filter( Boolean ).join( ', ' );
      const li = document.createElement( 'li' );
      li.className = 'map-group-item' + ( group.id === activeMarkerId ? ' map-group-item--active' : '' );
      li.dataset.id = group.id;
      li.innerHTML = `
        <div class="map-group-item__inner">
          ${ group.thumbnail ? `<img src="${escAttr( group.thumbnail )}" alt="" class="map-group-item__thumb" loading="lazy">` : '<div class="map-group-item__thumb map-group-item__thumb--placeholder"></div>' }
          <div class="map-group-item__text">
            <strong class="map-group-item__name">${esc( group.title )}</strong>
            ${ location ? `<span class="map-group-item__location">📍 ${esc( location )}</span>` : '' }
          </div>
        </div>
      `;

      li.addEventListener( 'click', function () {
        flyToGroup( group );
        setActiveGroup( group.id );
      } );

      listEl.appendChild( li );
    } );
  }

  /* ----------------------------------------------------------------
   * Filter logic
   * ---------------------------------------------------------------- */
  function getFilteredGroups() {
    const q = currentSearch.toLowerCase().trim();
    return groups.filter( function ( group ) {
      // State filter
      if ( currentState ) {
        const stateTerms = group._stateSlugs || [];
        if ( ! stateTerms.includes( currentState ) ) {
          // Fallback: match state abbreviation
          if ( ( group.state || '' ).toLowerCase() !== currentState.toLowerCase() &&
               ! ( group.state || '' ).toLowerCase().startsWith( currentState.toLowerCase() ) ) {
            // Simple approach: check state abbr
            if ( ( group.state || '' ).toUpperCase() !== currentState.toUpperCase() ) {
              // Also try matching full state name stored in state field
              if ( ! ( group.state || '' ).toLowerCase().includes( currentState.toLowerCase() ) ) {
                return false;
              }
            }
          }
        }
      }
      // Search filter
      if ( q ) {
        const haystack = [
          group.title,
          group.city,
          group.state,
          group.excerpt,
        ].filter( Boolean ).join( ' ' ).toLowerCase();
        if ( ! haystack.includes( q ) ) return false;
      }
      return true;
    } );
  }

  function applyFilters() {
    const filtered = getFilteredGroups();
    buildSidebarList( filtered );

    // Show/hide markers
    clusterGroup.clearLayers();
    filtered.forEach( function ( group ) {
      const marker = markerMap[ group.id ];
      if ( marker ) clusterGroup.addLayer( marker );
    } );
  }

  /* ----------------------------------------------------------------
   * Active group management
   * ---------------------------------------------------------------- */
  function setActiveGroup( id ) {
    // Reset previous
    if ( activeMarkerId && markerMap[ activeMarkerId ] ) {
      markerMap[ activeMarkerId ].setIcon( makeIcon( false ) );
    }
    activeMarkerId = id;

    // Activate new marker
    if ( markerMap[ id ] ) {
      markerMap[ id ].setIcon( makeIcon( true ) );
    }

    // Update sidebar active state
    document.querySelectorAll( '.map-group-item' ).forEach( function ( li ) {
      li.classList.toggle( 'map-group-item--active', parseInt( li.dataset.id, 10 ) === id );
    } );

    // Scroll active item into view in sidebar
    const activeItem = listEl ? listEl.querySelector( '.map-group-item--active' ) : null;
    if ( activeItem ) {
      activeItem.scrollIntoView( { behavior: 'smooth', block: 'nearest' } );
    }
  }

  function flyToGroup( group ) {
    map.flyTo( [ group.lat, group.lng ], 13, { duration: 1 } );
    setTimeout( function () {
      if ( markerMap[ group.id ] ) {
        markerMap[ group.id ].openPopup();
      }
    }, 1000 );
  }

  /* ----------------------------------------------------------------
   * Event listeners
   * ---------------------------------------------------------------- */
  if ( searchEl ) {
    let debounceTimer;
    searchEl.addEventListener( 'input', function () {
      clearTimeout( debounceTimer );
      debounceTimer = setTimeout( function () {
        currentSearch = searchEl.value;
        applyFilters();
      }, 250 );
    } );
  }

  if ( stateFilter ) {
    stateFilter.addEventListener( 'change', function () {
      currentState = stateFilter.value;
      applyFilters();
    } );
  }

  /* ----------------------------------------------------------------
   * Init
   * ---------------------------------------------------------------- */
  buildSidebarList( groups );

  /* ----------------------------------------------------------------
   * Escape helpers
   * ---------------------------------------------------------------- */
  function esc( str ) {
    if ( ! str ) return '';
    const div = document.createElement( 'div' );
    div.appendChild( document.createTextNode( str ) );
    return div.innerHTML;
  }
  function escAttr( str ) {
    if ( ! str ) return '';
    return str.replace( /"/g, '&quot;' ).replace( /'/g, '&#39;' );
  }

} )();
