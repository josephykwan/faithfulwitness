/**
 * Faithful Witness — Interactive Network Map
 *
 * Reads from assets/data/network-locations.json.
 * Renders colored pins by org type, syncs with partner card list below.
 *
 * Pin colors:
 *   national-partner  → navy  (#1B4F72)
 *   local-church      → amber (#D4750A)
 *   organizing-group  → teal  (#1A5A6A)
 */

( function () {
    'use strict';

    var PIN_COLORS = {
        'national-partner': '#1B4F72',
        'local-church':     '#D4750A',
        'organizing-group': '#1A5A6A',
    };

    var DEFAULT_COLOR = '#6B7280';

    var map;
    var markersByLocationId = {};

    function makeCircleIcon( color ) {
        return L.divIcon( {
            className: '',
            html: '<span style="display:block;width:18px;height:18px;border-radius:50%;background:' + color + ';border:2.5px solid rgba(255,255,255,0.9);box-shadow:0 1px 4px rgba(0,0,0,0.35);"></span>',
            iconSize:   [ 18, 18 ],
            iconAnchor: [ 9, 9 ],
            popupAnchor:[ 0, -14 ],
        } );
    }

    function buildPopupHTML( loc ) {
        var focusTags = '';
        if ( loc.focus && loc.focus.length ) {
            focusTags = loc.focus.map( function ( f ) {
                return '<span style="display:inline-block;background:rgba(255,255,255,0.15);color:#fff;font-size:11px;padding:2px 8px;border-radius:20px;margin:2px 2px 0 0;">' + f + '</span>';
            } ).join( '' );
        }

        var linkHTML = '';
        if ( loc.url ) {
            linkHTML = '<a href="' + loc.url + '" target="_blank" rel="noopener noreferrer" style="color:#fff;font-weight:600;font-size:12px;text-decoration:none;border-bottom:1px solid rgba(255,255,255,0.5);">Visit Website ↗</a>';
        }

        var color = PIN_COLORS[ loc.type ] || DEFAULT_COLOR;

        return '<div style="background:' + color + ';color:#fff;border-radius:8px;padding:12px 14px;min-width:200px;font-family:Inter,sans-serif;">' +
            '<strong style="font-size:14px;display:block;margin-bottom:4px;">' + loc.name + '</strong>' +
            ( loc.city ? '<span style="font-size:12px;opacity:0.85;">' + loc.city + ( loc.state ? ', ' + loc.state : '' ) + '</span><br>' : '' ) +
            ( loc.description ? '<p style="font-size:12px;margin:6px 0;opacity:0.9;line-height:1.4;">' + loc.description + '</p>' : '' ) +
            ( focusTags ? '<div style="margin-top:4px;">' + focusTags + '</div>' : '' ) +
            ( linkHTML ? '<div style="margin-top:8px;">' + linkHTML + '</div>' : '' ) +
            '</div>';
    }

    function initMap( locations ) {
        map = L.map( 'fw-network-map', {
            center:    [ 38.5, -96 ],
            zoom:      4,
            scrollWheelZoom: false,
        } );

        L.tileLayer( 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> © <a href="https://carto.com/attributions">CARTO</a>',
            subdomains:  'abcd',
            maxZoom:     19,
        } ).addTo( map );

        locations.forEach( function ( loc ) {
            if ( ! loc.lat || ! loc.lng ) return;

            var color  = PIN_COLORS[ loc.type ] || DEFAULT_COLOR;
            var icon   = makeCircleIcon( color );
            var marker = L.marker( [ loc.lat, loc.lng ], { icon: icon } );

            marker.bindPopup( buildPopupHTML( loc ), {
                maxWidth:    260,
                className:   'fw-map-popup',
            } );

            marker.addTo( map );
            markersByLocationId[ loc.id ] = marker;
        } );
    }

    function syncCardToPin( locationId ) {
        var marker = markersByLocationId[ locationId ];
        if ( ! marker || ! map ) return;
        map.setView( marker.getLatLng(), 7, { animate: true } );
        marker.openPopup();
    }

    function bindCardClicks() {
        var cards = document.querySelectorAll( '.partner-card[data-location-id]' );
        cards.forEach( function ( card ) {
            card.addEventListener( 'click', function () {
                syncCardToPin( card.dataset.locationId );
            } );
            card.style.cursor = 'pointer';
            card.title = 'Click to show on map';
        } );
    }

    function loadAndInit() {
        if ( typeof fwNetworkConfig === 'undefined' || ! fwNetworkConfig.jsonUrl ) return;

        fetch( fwNetworkConfig.jsonUrl )
            .then( function ( r ) { return r.json(); } )
            .then( function ( data ) {
                var locations = ( data && data.locations ) ? data.locations : [];
                initMap( locations );
                bindCardClicks();
            } )
            .catch( function () {
                // Fallback: init empty map
                initMap( [] );
            } );
    }

    if ( document.readyState === 'loading' ) {
        document.addEventListener( 'DOMContentLoaded', loadAndInit );
    } else {
        loadAndInit();
    }
} )();
