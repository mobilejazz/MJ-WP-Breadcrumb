<?php
    /*!
     * MJ WordPress Breadcrumb
     * Description: A lightweight, customisable function to generate and display a breadcrumb for WordPress.
     * Version: 1.1
     * Author: Mobile Jazz
     * Url: http://www.mobilejazz.com/
     * License: http://www.apache.org/licenses/LICENSE-2.0
     */

    if ( ! function_exists( 'mj_wp_breadcrumb' ) ) {
        function mj_wp_breadcrumb ( $list_style = 'ol', $list_id = 'breadcrumb', $list_class = 'breadcrumb', $active_class = 'active', $echo = false ) {
            // Get text domain for translations
            $theme = wp_get_theme();
            $text_domain =  $theme->get( 'TextDomain' );

            // Open list
            $breadcrumb = '<' . $list_style . ' id="' . $list_id . '" class="' . $list_class . '">';

            // Front page
            if ( is_front_page() ) {
                $breadcrumb .= '<li class="' . $active_class . '">' . get_bloginfo( 'name' ). '</li>';
            } else {
                $breadcrumb .= '<li><a href="' . home_url() . '">' . get_bloginfo( 'name' ) . '</a></li>';
            }

            // Blog archive
            if ( 'page' == get_option( 'show_on_front' ) && get_option( 'page_for_posts' ) ) {
                $blog_page_id = get_option( 'page_for_posts' );

                if ( is_home() ) {
                    $breadcrumb .= '<li class="' . $active_class . '">' . get_the_title( $blog_page_id )  . '</li>';
                } else if ( is_category() || is_tag() || is_author() || is_date() || is_singular( 'post' ) ) {
                    $breadcrumb .= '<li><a href="' . get_permalink( $blog_page_id ) . '">' . get_the_title( $blog_page_id )  . '</a></li>';
                }
            }

            // Category, tag, author and date archives
            if ( is_archive() && ! is_tax() && ! is_post_type_archive() ) {
                $breadcrumb .= '<li class="' . $active_class . '">';

                // Title of archive
                if ( is_category() ) {
                    $breadcrumb .= single_cat_title( '', false );
                } else if ( is_tag() ) {
                    $breadcrumb .= single_tag_title( '', false );
                } else if ( is_author() ) {
                    $breadcrumb .= get_the_author();
                } else if ( is_date() ) {
                    if ( is_day() ) {
                        $breadcrumb .= get_the_time( 'F j, Y' );
                    } else if ( is_month() ) {
                        $breadcrumb .= get_the_time( 'F, Y' );
                    } else if ( is_year() ) {
                        $breadcrumb .= get_the_time( 'Y' );
                    }
                }

                $breadcrumb .= '</li>';
            }

            // Posts
            if ( is_singular( 'post' ) ) {

                // Post categories
                $post_cats = get_the_category();

                if ( $post_cats[0] ) {
                    $breadcrumb .= '<li><a href="' . get_category_link( $post_cats[0]->term_id ) . '">' . $post_cats[0]->name . '</a></li>';
                }

                // Post title
                $breadcrumb .= '<li class="' . $active_class . '">' . get_the_title() . '</li>';
            }

            // Pages
            if ( is_page() && ! is_front_page() ) {
                $post = get_post( get_the_ID() );

                // Page parents
                if ( $post->post_parent ) {
                    $parent_id  = $post->post_parent;
                    $crumbs = array();

                    while ( $parent_id ) {
                        $page = get_page( $parent_id );
                        $crumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
                        $parent_id  = $page->post_parent;
                    }

                    $crumbs = array_reverse( $crumbs );

                    foreach ( $crumbs as $crumb ) {
                        $breadcrumb .= '<li>' . $crumb . '</li>';
                    }
                }

                // Page title
                $breadcrumb .=  '<li class="' . $active_class . '">' . get_the_title() . '</li>';
            }

            // Attachments
            if ( is_attachment() ) {
                // Attachment parent
                $post = get_post( get_the_ID() );

                if ( $post->post_parent ) {
                    $breadcrumb .= '<li><a href="' . get_permalink( $post->post_parent ) . '">' . get_the_title( $post->post_parent ) . '</a></li>';
                }

                // Attachment title
                $breadcrumb .= '<li class="' . $active_class . '">' . get_the_title() . '</li>';
            }

            // Search
            if ( is_search() ) {
                $breadcrumb .= '<li class="' . $active_class . '">' . __( 'Search', $text_domain ) . '</li>';
            }

            // 404
            if ( is_404() ) {
                $breadcrumb .= '<li class="' . $active_class . '">' . __( '404', $text_domain ) . '</li>';
            }

            // Custom Post Type Archive
			if ( is_post_type_archive() ) {
				$breadcrumb .= '<li class="' . $active_class . '">' . post_type_archive_title( '', false ) . '</li>';
			}

			// Custom Taxonomies
			if ( is_tax() ) {
				// Get the post types the taxonomy is attached to
				$current_term = get_queried_object();

				$attached_to = array();
				$post_types = get_post_types();

				foreach ( $post_types as $post_type ) {
					$taxonomies = get_object_taxonomies( $post_type );

					if ( in_array( $current_term->taxonomy, $taxonomies ) ) {
						$attached_to[] = $post_type;
					}
				}

				// Post type archive link
				$output = false;

				foreach ( $attached_to as $post_type ) {
					$cpt_obj = get_post_type_object( $post_type );

					if ( ! $output && get_post_type_archive_link( $cpt_obj->name ) ) {
						$breadcrumb .= '<li><a href="' . get_post_type_archive_link( $cpt_obj->name ) . '">' . $cpt_obj->labels->name . '</a></li>';
						$output = true;
					}
				}

				// Term title
				$breadcrumb .= '<li class="' . $active_class . '">' . single_term_title( '', false ) . '</li>';
			}

			// Custom Post Types
			if ( is_single() && get_post_type() != 'post' && get_post_type() != 'attachment' ) {
				$cpt_obj = get_post_type_object( get_post_type() );

				// Is cpt hierarchical like pages or posts?
				if ( is_post_type_hierarchical( $cpt_obj->name ) ) {
					// Like pages

					// Cpt archive
					if ( get_post_type_archive_link( $cpt_obj->name ) ) {
						$breadcrumb .= '<li><a href="' . get_post_type_archive_link( $cpt_obj->name ) . '">' . $cpt_obj->labels->name . '</a></li>';
					}

					// Cpt parents
					$post = get_post( get_the_ID() );

					if ( $post->post_parent ) {
						$parent_id  = $post->post_parent;
						$crumbs = array();

						while ( $parent_id ) {
							$page = get_page( $parent_id );
							$crumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
							$parent_id  = $page->post_parent;
						}

						$crumbs = array_reverse( $crumbs );

						foreach ( $crumbs as $crumb ) {
							$breadcrumb .= '<li>' . $crumb . '</li>';
						}
					}
				} else {
					// Like posts

					// Cpt archive
					if ( get_post_type_archive_link( $cpt_obj->name ) ) {
						$breadcrumb .= '<li><a href="' . get_post_type_archive_link( $cpt_obj->name ) . '">' . $cpt_obj->labels->name . '</a></li>';
					}

					// Get cpt taxonomies
					$cpt_taxes = get_object_taxonomies( $cpt_obj->name );

					if ( $cpt_taxes && is_taxonomy_hierarchical( $cpt_taxes[0] ) ) {
						// Other taxes attached to the cpt could be hierachical, so need to look into that.
						$cpt_terms = get_the_terms( get_the_ID(), $cpt_taxes[0] );

						if ( is_array( $cpt_terms ) ) {
							$output = false;

							foreach( $cpt_terms as $cpt_term ) {
								if ( ! $output ) {
									$breadcrumb .= '<li><a href="' . get_term_link( $cpt_term->name, $cpt_taxes[0] ) . '">' . $cpt_term->name . '</a></li>';
									$output = true;
								}
							}
						}
					}
				}

				// Cpt title
				$breadcrumb .= '<li class="' . $active_class . '">' . get_the_title() . '</li>';
			}

            // Close list
            $breadcrumb .= '</' . $list_style . '>';

            // Ouput
            if ( $echo ) {
                echo $breadcrumb;
            } else {
                return $breadcrumb;
            }
        }
    }
?>
