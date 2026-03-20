/**
 * Faithful Witness — Main JavaScript
 *
 * - Mobile nav toggle
 * - Events page tab switching + state filtering
 * - Initiative inline resource type filtering
 * - Smooth scroll for anchor links
 */

( function () {
  'use strict';

  /* ----------------------------------------------------------------
   * Mobile nav toggle
   * ---------------------------------------------------------------- */
  const navToggle = document.getElementById( 'nav-toggle' );
  const primaryNav = document.getElementById( 'primary-nav' );

  if ( navToggle && primaryNav ) {
    navToggle.addEventListener( 'click', function () {
      const isOpen = primaryNav.classList.toggle( 'is-open' );
      navToggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
      document.body.style.overflow = isOpen ? 'hidden' : '';
    } );

    // Close on outside click
    document.addEventListener( 'click', function ( e ) {
      if ( ! navToggle.contains( e.target ) && ! primaryNav.contains( e.target ) ) {
        primaryNav.classList.remove( 'is-open' );
        navToggle.setAttribute( 'aria-expanded', 'false' );
        document.body.style.overflow = '';
      }
    } );

    // Close on Escape
    document.addEventListener( 'keydown', function ( e ) {
      if ( e.key === 'Escape' && primaryNav.classList.contains( 'is-open' ) ) {
        primaryNav.classList.remove( 'is-open' );
        navToggle.setAttribute( 'aria-expanded', 'false' );
        navToggle.focus();
        document.body.style.overflow = '';
      }
    } );
  }

  /* ----------------------------------------------------------------
   * Events page: tab switching
   * ---------------------------------------------------------------- */
  const tabs   = document.querySelectorAll( '.events-tab[data-tab]' );
  const panels = document.querySelectorAll( '.events-panel' );

  if ( tabs.length ) {
    tabs.forEach( function ( tab ) {
      tab.addEventListener( 'click', function () {
        const target = tab.dataset.tab;

        tabs.forEach( function ( t ) {
          t.classList.remove( 'active' );
          t.setAttribute( 'aria-selected', 'false' );
        } );
        tab.classList.add( 'active' );
        tab.setAttribute( 'aria-selected', 'true' );

        panels.forEach( function ( panel ) {
          if ( panel.id === 'panel-' + target ) {
            panel.removeAttribute( 'hidden' );
            panel.classList.add( 'events-panel--active' );
          } else {
            panel.setAttribute( 'hidden', '' );
            panel.classList.remove( 'events-panel--active' );
          }
        } );
      } );
    } );
  }

  /* ----------------------------------------------------------------
   * Events page: state filter
   * Hides event cards that don't belong to the selected state
   * ---------------------------------------------------------------- */
  const stateFilter = document.getElementById( 'events-state-filter' );
  if ( stateFilter ) {
    stateFilter.addEventListener( 'change', function () {
      const selected = stateFilter.value.toLowerCase();
      document.querySelectorAll( '.event-card' ).forEach( function ( card ) {
        if ( ! selected ) {
          card.closest( 'article' )?.style && ( card.style.display = '' );
          return;
        }
        // Look for the state in the event detail text
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes( selected ) ? '' : 'none';
      } );

      // Hide empty month groups
      document.querySelectorAll( '.events-month-group' ).forEach( function ( group ) {
        const visibleCards = group.querySelectorAll( '.event-card:not([style*="display: none"])' );
        group.style.display = visibleCards.length === 0 ? 'none' : '';
      } );
    } );
  }

  /* ----------------------------------------------------------------
   * Initiative page: inline resource type filter
   * ---------------------------------------------------------------- */
  const resTypeBtns = document.querySelectorAll( '.resource-type-tabs .filter-pill[data-res-type]' );
  const initResGrid = document.getElementById( 'initiative-resources-grid' );

  if ( resTypeBtns.length && initResGrid ) {
    resTypeBtns.forEach( function ( btn ) {
      btn.addEventListener( 'click', function () {
        const type = btn.dataset.resType;

        resTypeBtns.forEach( function ( b ) { b.classList.remove( 'active' ); } );
        btn.classList.add( 'active' );

        initResGrid.querySelectorAll( '.resource-card' ).forEach( function ( card ) {
          if ( ! type || card.dataset.type === type ) {
            card.style.display = '';
          } else {
            card.style.display = 'none';
          }
        } );
      } );
    } );
  }

  /* ----------------------------------------------------------------
   * Smooth scroll for in-page anchor links (e.g., ← Back to resources ↓)
   * ---------------------------------------------------------------- */
  document.querySelectorAll( 'a[href^="#"]' ).forEach( function ( link ) {
    link.addEventListener( 'click', function ( e ) {
      const id  = link.getAttribute( 'href' ).substring( 1 );
      const target = document.getElementById( id );
      if ( target ) {
        e.preventDefault();
        const headerH = document.getElementById( 'site-header' )?.offsetHeight || 80;
        const top = target.getBoundingClientRect().top + window.scrollY - headerH - 16;
        window.scrollTo( { top, behavior: 'smooth' } );
      }
    } );
  } );

  /* ----------------------------------------------------------------
   * Sticky header: add shadow on scroll
   * ---------------------------------------------------------------- */
  const header = document.getElementById( 'site-header' );
  if ( header ) {
    let ticking = false;
    window.addEventListener( 'scroll', function () {
      if ( ! ticking ) {
        window.requestAnimationFrame( function () {
          header.classList.toggle( 'is-scrolled', window.scrollY > 20 );
          ticking = false;
        } );
        ticking = true;
      }
    } );
  }

} )();
