<?php

// Don't load directly
if ( !defined('ABSPATH') ) { die('-1'); }

// OJO, manten siempre esta copia de seguridad porque estoy cambiando ficheros
//Estos esquemas vienen de json desde javascript

$schemes = array(

    'FRESH RED' => '{"heading_font":"Oswald","body_font":"Anaheim","blog_title_font":"Fjalla One","general_backcolor":"#ff3300","general_pattern":"","general_pattern_scroll":"fixed","font_size_h1":"49px","font_size_h2":"24px","font_size_h3":"20px","font_size_h4":"18px","font_size_h5":"11px","font_size_h6":"10px","header_top_font_size":"10px","header_top_backcolor":"#ff3300","header_top_color":"#ffffff","header_top_menu_hover_color":"#000000","header_top_link_color":"#0c0c0c","header_top_hoverlink_color":"#2d2d2d","header_top_division_color":"#bc0000","header_top_pattern":"","header_bottom_font_size":"13px","header_bottom_backcolor":"#ff3300","header_bottom_color":"#ffffff","header_bottom_menu_hover_color":"#000000","header_bottom_link_color":"#ffffff","header_bottom_hoverlink_color":"#000000","header_bottom_division_color":"#bc0000","header_bottom_border_color":"#bc0000","header_bottom_pattern":"","left_font_size":"14px","left_backcolor":"#ffffff","left_heading_color":"#000000","left_color":"#3d3d3d","left_border_color_line":"#ffffff","left_border_color":"#c4c4c4","left_widget_backcolor":"transparent","left_widget_margin":"0px","left_border_color_menu":"#c4c4c4","left_link_color":"#ff3300","left_hoverlink_color":"#000000","left_pattern":"","left_pattern_scroll":"fixed","main_font_size":"16px","main_meta_font_size":"11px","main_backcolor":"#ffffff","main_heading_color":"#000000","main_color":"#3d3d3d","main_hig_backcolor":"#ff3300","main_hig_color":"#ffffff","main_hig_hover_color":"#000000","main_alt_backcolor":"#ffffff","main_alt_color":"#c4c4c4","main_window_border_color":"#c4c4c4","main_border_color":"#ff3300","main_link_color":"#ff3300","main_hoverlink_color":"#3d3d3d","main_link_back_color":"#eadede","main_pattern":"","main_pattern_scroll":"fixed","right_font_size":"14px","right_backcolor":"#ffffff","right_heading_color":"#000000","right_color":"#3d3d3d","right_border_color_line":"#ffffff","right_border_color":"#c4c4c4","right_widget_backcolor":"transparent","right_widget_margin":"0px","right_border_color_menu":"#c4c4c4","right_link_color":"#ff3300","right_hoverlink_color":"#000000","right_pattern":"","right_pattern_scroll":"scroll","footer_font_size":"14px","footer_backcolor":"#ffffff","footer_heading_color":"#000000","footer_color":"#3a3a3a","footer_border_color_line":"#c4c4c4","footer_border_color":"#ffffff","footer_border_color_menu":"#c6c6c6","footer_link_color":"#ff3300","footer_hoverlink_color":"#000000","footer_pattern":"","footer_pattern_scroll":"scroll","socket_font_size":"11px","socket_backcolor":"#ff3300","socket_color":"#ffffff","socket_division_color":"#ffffff","socket_border_color":"#ff3300","socket_link_color":"#ffffff","socket_hoverlink_color":"#000000","socket_pattern":"","socket_pattern_scroll":"fixed"}',
    'HARD YELLOW' => '{"heading_font":"Helvetica-websafe","body_font":"Cabin","blog_title_font":"Helvetica-websafe","general_backcolor":"#ffffff","general_pattern":"","general_pattern_scroll":"fixed","font_size_h1":"47px","font_size_h2":"24px","font_size_h3":"17px","font_size_h4":"14px","font_size_h5":"12px","font_size_h6":"10px","header_top_font_size":"11px","header_top_backcolor":"#000000","header_top_color":"#eded00","header_top_menu_hover_color":"#ffffff","header_top_link_color":"#ffffff","header_top_hoverlink_color":"#eded00","header_top_division_color":"#eded00","header_top_pattern":"","header_bottom_font_size":"13px","header_bottom_backcolor":"#eded00","header_bottom_color":"#000000","header_bottom_menu_hover_color":"#4c4c4c","header_bottom_link_color":"#000000","header_bottom_hoverlink_color":"#000000","header_bottom_division_color":"#000000","header_bottom_border_color":"#eded00","header_bottom_pattern":"","left_font_size":"13px","left_backcolor":"#ffffff","left_heading_color":"#000000","left_color":"#515151","left_border_color_line":"#ffffff","left_border_color":"#ffffff","left_widget_backcolor":"#ffffff","left_widget_margin":"0px","left_border_color_menu":"#c9c9c9","left_link_color":"#3d3d3d","left_hoverlink_color":"#eded00","left_pattern":"","left_pattern_scroll":"fixed","main_font_size":"14px","main_meta_font_size":"11px","main_backcolor":"#ffffff","main_heading_color":"#000000","main_color":"#000000","main_hig_backcolor":"#000000","main_hig_color":"#eded00","main_hig_hover_color":"#6d6d6d","main_alt_backcolor":"#ffffff","main_alt_color":"#eded00","main_window_border_color":"#000000","main_border_color":"#848484","main_link_color":"#3d3d3d","main_hoverlink_color":"#eded00","main_link_back_color":"#ffffff","main_pattern":"","main_pattern_scroll":"fixed","right_font_size":"13px","right_backcolor":"#ffffff","right_heading_color":"#000000","right_color":"#515151","right_border_color_line":"#ffffff","right_border_color":"#ffffff","right_widget_backcolor":"#ffffff","right_widget_margin":"0px","right_border_color_menu":"#c4c4c4","right_link_color":"#000000","right_hoverlink_color":"#eded00","right_pattern":"","right_pattern_scroll":"scroll","footer_font_size":"13px","footer_backcolor":"#eded00","footer_heading_color":"#000000","footer_color":"#3f3f3f","footer_border_color_line":"#eded00","footer_border_color":"#eded00","footer_border_color_menu":"#eded00","footer_link_color":"#000000","footer_hoverlink_color":"#000000","footer_pattern":"","footer_pattern_scroll":"scroll","socket_font_size":"11px","socket_backcolor":"#000000","socket_color":"#eded00","socket_division_color":"#eded00","socket_border_color":"#000000","socket_link_color":"#ffffff","socket_hoverlink_color":"#eded00","socket_pattern":"","socket_pattern_scroll":"fixed"}',
    'MINIMAL WHITE' => '{"heading_font":"Orbitron","body_font":"Merriweather","blog_title_font":"Orbitron","general_backcolor":"#ffffff","general_pattern":"","general_pattern_scroll":"fixed","font_size_h1":"47px","font_size_h2":"29px","font_size_h3":"17px","font_size_h4":"16px","font_size_h5":"12px","font_size_h6":"10px","header_top_font_size":"12px","header_top_backcolor":"#ffffff","header_top_color":"#565656","header_top_menu_hover_color":"#212121","header_top_link_color":"#548d99","header_top_hoverlink_color":"#ed7221","header_top_division_color":"#ffffff","header_top_pattern":"","header_bottom_font_size":"14px","header_bottom_backcolor":"#ffffff","header_bottom_color":"#3a3a3a","header_bottom_menu_hover_color":"#548d99","header_bottom_link_color":"#000000","header_bottom_hoverlink_color":"#ed7221","header_bottom_division_color":"#ffffff","header_bottom_border_color":"#e0e0e0","header_bottom_pattern":"","left_font_size":"13px","left_backcolor":"#ffffff","left_heading_color":"#000000","left_color":"#565656","left_border_color_line":"#ffffff","left_border_color":"#c1c1c1","left_widget_backcolor":"#ffffff","left_widget_margin":"0px","left_border_color_menu":"#e8e8e8","left_link_color":"#548d99","left_hoverlink_color":"#ed7221","left_pattern":"","left_pattern_scroll":"fixed","main_font_size":"14px","main_meta_font_size":"11px","main_backcolor":"#ffffff","main_heading_color":"#0a0a0a","main_color":"#565656","main_hig_backcolor":"#ffffff","main_hig_color":"#000000","main_hig_hover_color":"#548d99","main_alt_backcolor":"#ffffff","main_alt_color":"#548d99","main_window_border_color":"#ffffff","main_border_color":"#ffffff","main_link_color":"#548d99","main_hoverlink_color":"#ed7221","main_link_back_color":"#ffffff","main_pattern":"","main_pattern_scroll":"fixed","right_font_size":"13px","right_backcolor":"#ffffff","right_heading_color":"#000000","right_color":"#515151","right_border_color_line":"#ffffff","right_border_color":"#c1c1c1","right_widget_backcolor":"#ffffff","right_widget_margin":"0px","right_border_color_menu":"#ededed","right_link_color":"#548d99","right_hoverlink_color":"#ed7221","right_pattern":"","right_pattern_scroll":"scroll","footer_font_size":"13px","footer_backcolor":"#ffffff","footer_heading_color":"#0a0a0a","footer_color":"#565656","footer_border_color_line":"#e0e0e0","footer_border_color":"#ffffff","footer_border_color_menu":"#e0e0e0","footer_link_color":"#548d99","footer_hoverlink_color":"#ed7221","footer_pattern":"","footer_pattern_scroll":"scroll","socket_font_size":"11px","socket_backcolor":"#ffffff","socket_color":"#565656","socket_division_color":"#ffffff","socket_border_color":"#e0e0e0","socket_link_color":"#548d99","socket_hoverlink_color":"#ed7221","socket_pattern":"","socket_pattern_scroll":"fixed"}',
    'BLACK and RED' => '{"heading_font":"Allerta","body_font":"Carme","blog_title_font":"Allerta","general_backcolor":"#000000","general_pattern":"dots3_black_t","general_pattern_scroll":"fixed","font_size_h1":"36px","font_size_h2":"28px","font_size_h3":"20px","font_size_h4":"16px","font_size_h5":"13px","font_size_h6":"11px","header_top_font_size":"11px","header_top_backcolor":"#000000","header_top_color":"#afafaf","header_top_menu_hover_color":"#ffffff","header_top_link_color":"#ff3300","header_top_hoverlink_color":"#930000","header_top_division_color":"#444444","header_top_pattern":"","header_bottom_font_size":"13px","header_bottom_backcolor":"#000000","header_bottom_color":"#d1d1d1","header_bottom_menu_hover_color":"#ffffff","header_bottom_link_color":"#ff3300","header_bottom_hoverlink_color":"#930000","header_bottom_division_color":"#444444","header_bottom_border_color":"#444444","header_bottom_pattern":"","left_font_size":"13px","left_backcolor":"#000000","left_heading_color":"#f4f4f4","left_color":"#a5a5a5","left_border_color_line":"#000000","left_border_color":"#eaeaea","left_widget_backcolor":"transparent","left_widget_margin":"0px","left_border_color_menu":"#444444","left_link_color":"#ff3300","left_hoverlink_color":"#930000","left_pattern":"","left_pattern_scroll":"fixed","main_font_size":"14px","main_meta_font_size":"11px","main_backcolor":"#000000","main_heading_color":"#ff3300","main_color":"#a3a3a3","main_hig_backcolor":"#000000","main_hig_color":"#ff3300","main_hig_hover_color":"","main_alt_backcolor":"#000000","main_alt_color":"#686868","main_window_border_color":"#000000","main_border_color":"#444444","main_link_color":"#ff3300","main_hoverlink_color":"#930000","main_link_back_color":"#000000","main_pattern":"","main_pattern_scroll":"fixed","right_font_size":"13px","right_backcolor":"#000000","right_heading_color":"#f4f4f4","right_color":"#a5a5a5","right_border_color_line":"#000000","right_border_color":"#eaeaea","right_widget_backcolor":"transparent","right_widget_margin":"0px","right_border_color_menu":"#444444","right_link_color":"#ff3300","right_hoverlink_color":"#930000","right_pattern":"","right_pattern_scroll":"fixed","footer_font_size":"13px","footer_backcolor":"#000000","footer_heading_color":"#ffffff","footer_color":"#bababa","footer_border_color_line":"#353535","footer_border_color":"#666666","footer_border_color_menu":"#3d3d3d","footer_link_color":"#ff3300","footer_hoverlink_color":"#930000","footer_pattern":"dots3_black_t","footer_pattern_scroll":"scroll","socket_font_size":"11px","socket_backcolor":"#000000","socket_color":"#a3a3a3","socket_division_color":"#3d3d3d","socket_border_color":"#000000","socket_link_color":"#ff3300","socket_hoverlink_color":"#930000","socket_pattern":"","socket_pattern_scroll":"fixed"}',
    'PATTERN' => '{"heading_font":"Coda","body_font":"Coda","blog_title_font":"Coda","general_backcolor":"#e8e8e8","general_pattern":"horizontal1_t","general_pattern_scroll":"fixed","font_size_h1":"47px","font_size_h2":"24px","font_size_h3":"17px","font_size_h4":"14px","font_size_h5":"12px","font_size_h6":"10px","header_top_font_size":"11px","header_top_backcolor":"#ffffff","header_top_color":"#3d3d3d","header_top_menu_hover_color":"#000000","header_top_link_color":"#ed7221","header_top_hoverlink_color":"#548d99","header_top_division_color":"transparent","header_top_pattern":"vertical3_t","header_bottom_font_size":"13px","header_bottom_backcolor":"#ffffff","header_bottom_color":"#3d3d3d","header_bottom_menu_hover_color":"#0a0a0a","header_bottom_link_color":"#ed7221","header_bottom_hoverlink_color":"#548d99","header_bottom_division_color":"transparent","header_bottom_border_color":"#e8e8e8","header_bottom_pattern":"vertical3_t","left_font_size":"13px","left_backcolor":"#ffffff","left_heading_color":"#3d3d3d","left_color":"#3d3d3d","left_border_color_line":"#ffffff","left_border_color":"#3d3d3d","left_widget_backcolor":"transparent","left_widget_margin":"0px","left_border_color_menu":"#e8e8e8","left_link_color":"#ed7221","left_hoverlink_color":"#548d99","left_pattern":"horizontal3_t","left_pattern_scroll":"fixed","main_font_size":"12px","main_meta_font_size":"10px","main_backcolor":"#ffffff","main_heading_color":"#3d3d3d","main_color":"#3d3d3d","main_hig_backcolor":"#ffffff","main_hig_color":"#000000","main_hig_hover_color":"#548d99","main_alt_backcolor":"#ffffff","main_alt_color":"#d3d3d3","main_window_border_color":"#ffffff","main_border_color":"#8c8c8c","main_link_color":"#ed7221","main_hoverlink_color":"#548d99","main_link_back_color":"#ffffff","main_pattern":"","main_pattern_scroll":"fixed","right_font_size":"13px","right_backcolor":"#ffffff","right_heading_color":"#3d3d3d","right_color":"#3d3d3d","right_border_color_line":"#ffffff","right_border_color":"#3d3d3d","right_widget_backcolor":"transparent","right_widget_margin":"0px","right_border_color_menu":"#ededed","right_link_color":"#ed7221","right_hoverlink_color":"#548d99","right_pattern":"horizontal3_t","right_pattern_scroll":"scroll","footer_font_size":"12px","footer_backcolor":"#ffffff","footer_heading_color":"#3d3d3d","footer_color":"#3d3d3d","footer_border_color_line":"#3d3d3d","footer_border_color":"#c9c9c9","footer_border_color_menu":"#eaeaea","footer_link_color":"#ed7221","footer_hoverlink_color":"#548d99","footer_pattern":"vertical3_t","footer_pattern_scroll":"scroll","socket_font_size":"11px","socket_backcolor":"#ffffff","socket_color":"#3d3d3d","socket_division_color":"#ffffff","socket_border_color":"#3d3d3d","socket_link_color":"#ed7221","socket_hoverlink_color":"#548d99","socket_pattern":"vertical4_t","socket_pattern_scroll":"fixed"}',
    'BLUE among GRAY'  => '{"heading_font":"Cardo","body_font":"Cardo","blog_title_font":"Cardo","general_backcolor":"#3d3d3d","general_pattern":"","general_pattern_scroll":"fixed","font_size_h1":"36px","font_size_h2":"24px","font_size_h3":"18px","font_size_h4":"16px","font_size_h5":"14px","font_size_h6":"12px","header_top_font_size":"11px","header_top_backcolor":"#3d3d3d","header_top_color":"#e8e8e8","header_top_menu_hover_color":"#bcbcbc","header_top_link_color":"#548d99","header_top_hoverlink_color":"#ed7221","header_top_division_color":"#4c4c4c","header_top_pattern":"","header_bottom_font_size":"14px","header_bottom_backcolor":"#3d3d3d","header_bottom_color":"#e8e8e8","header_bottom_menu_hover_color":"#bcbcbc","header_bottom_link_color":"#548d99","header_bottom_hoverlink_color":"#ed7221","header_bottom_division_color":"#4c4c4c","header_bottom_border_color":"#4c4c4c","header_bottom_pattern":"","left_font_size":"13px","left_backcolor":"#3d3d3d","left_heading_color":"#3d3d3d","left_color":"#3d3d3d","left_border_color_line":"#3d3d3d","left_border_color":"#4f4f4f","left_widget_backcolor":"#548d99","left_widget_margin":"15px","left_border_color_menu":"#4f4f4f","left_link_color":"#e8e8e8","left_hoverlink_color":"#ed7221","left_pattern":"","left_pattern_scroll":"fixed","main_font_size":"14px","main_meta_font_size":"11px","main_backcolor":"#3d3d3d","main_heading_color":"#548d99","main_color":"#e8e8e8","main_hig_backcolor":"#3d3d3d","main_hig_color":"#e8e8e8","main_hig_hover_color":"#548d99","main_alt_backcolor":"#3d3d3d","main_alt_color":"#548d99","main_window_border_color":"#3d3d3d","main_border_color":"#e8e8e8","main_link_color":"#548d99","main_hoverlink_color":"#ed7221","main_link_back_color":"#3d3d3d","main_pattern":"","main_pattern_scroll":"fixed","right_font_size":"13px","right_backcolor":"#3d3d3d","right_heading_color":"#e8e8e8","right_color":"#e8e8e8","right_border_color_line":"#3d3d3d","right_border_color":"#4f4f4f","right_widget_backcolor":"transparent","right_widget_margin":"15px","right_border_color_menu":"#4f4f4f","right_link_color":"#548d99","right_hoverlink_color":"#ed7221","right_pattern":"","right_pattern_scroll":"scroll","footer_font_size":"13px","footer_backcolor":"#3d3d3d","footer_heading_color":"#e8e8e8","footer_color":"#e8e8e8","footer_border_color_line":"#4f4f4f","footer_border_color":"#4f4f4f","footer_border_color_menu":"#4f4f4f","footer_link_color":"#548d99","footer_hoverlink_color":"#ed7221","footer_pattern":"","footer_pattern_scroll":"scroll","socket_font_size":"11px","socket_backcolor":"#3d3d3d","socket_color":"#e8e8e8","socket_division_color":"#595959","socket_border_color":"#595959","socket_link_color":"#548d99","socket_hoverlink_color":"#ed7221","socket_pattern":"","socket_pattern_scroll":"fixed"}',
    'FLOATING BOXES'  => '{"heading_font":"Coda","body_font":"Carme","blog_title_font":"Coda","general_backcolor":"#e8e8e8","general_pattern":"","general_pattern_scroll":"fixed","font_size_h1":"47px","font_size_h2":"29px","font_size_h3":"17px","font_size_h4":"16px","font_size_h5":"12px","font_size_h6":"10px","header_top_font_size":"12px","header_top_backcolor":"#e8e8e8","header_top_color":"#3d3d3d","header_top_menu_hover_color":"#3d3d3d","header_top_link_color":"#cc4422","header_top_hoverlink_color":"#ed7221","header_top_division_color":"#e8e8e8","header_top_pattern":"","header_bottom_font_size":"14px","header_bottom_backcolor":"#e8e8e8","header_bottom_color":"#3d3d3d","header_bottom_menu_hover_color":"#cc4422","header_bottom_link_color":"#cc4422","header_bottom_hoverlink_color":"#ed7221","header_bottom_division_color":"#e8e8e8","header_bottom_border_color":"#e8e8e8","header_bottom_pattern":"","left_font_size":"13px","left_backcolor":"#e8e8e8","left_heading_color":"#e8e8e8","left_color":"#e8e8e8","left_border_color_line":"e8e8e8","left_border_color":"#e8e8e8","left_widget_backcolor":"#3d3d3d","left_widget_margin":"15px","left_border_color_menu":"#5e5e5e","left_link_color":"#cc4422","left_hoverlink_color":"#e8e8e8","left_pattern":"","left_pattern_scroll":"fixed","main_font_size":"14px","main_meta_font_size":"11px","main_backcolor":"#e8e8e8","main_heading_color":"#3d3d3d","main_color":"#3d3d3d","main_hig_backcolor":"#3d3d3d","main_hig_color":"#e8e8e8","main_hig_hover_color":"#cc4422","main_alt_backcolor":"#ffffff","main_alt_color":"#828282","main_window_border_color":"#e8e8e8","main_border_color":"#3d3d3d","main_link_color":"#cc4422","main_hoverlink_color":"#3d3d3d","main_link_back_color":"#ffffff","main_pattern":"","main_pattern_scroll":"fixed","right_font_size":"13px","right_backcolor":"#e8e8e8","right_heading_color":"#e8e8e8","right_color":"#e8e8e8","right_border_color_line":"transparent","right_border_color":"#e8e8e8","right_widget_backcolor":"#3d3d3d","right_widget_margin":"15px","right_border_color_menu":"#5e5e5e","right_link_color":"#cc4422","right_hoverlink_color":"#e8e8e8","right_pattern":"","right_pattern_scroll":"scroll","footer_font_size":"13px","footer_backcolor":"#e8e8e8","footer_heading_color":"#3d3d3d","footer_color":"#565656","footer_border_color_line":"#e8e8e8","footer_border_color":"#e8e8e8","footer_border_color_menu":"#3d3d3d","footer_link_color":"#cc4422","footer_hoverlink_color":"#3d3d3d","footer_pattern":"","footer_pattern_scroll":"scroll","socket_font_size":"11px","socket_backcolor":"#3d3d3d","socket_color":"#e8e8e8","socket_division_color":"#e8e8e8","socket_border_color":"#e8e8e8","socket_link_color":"#cc4422","socket_hoverlink_color":"#ed7221","socket_pattern":"","socket_pattern_scroll":"fixed"}',
    'WHITE WITH BLACK' => '{"heading_font":"Fjalla One","body_font":"Molengo","blog_title_font":"Fjalla One","general_backcolor":"#ffffff","general_pattern":"","general_pattern_scroll":"fixed","font_size_h1":"47px","font_size_h2":"29px","font_size_h3":"17px","font_size_h4":"16px","font_size_h5":"12px","font_size_h6":"10px","header_top_font_size":"12px","header_top_backcolor":"#ffffff","header_top_color":"#565656","header_top_menu_hover_color":"#212121","header_top_link_color":"#8db538","header_top_hoverlink_color":"#ffaa00","header_top_division_color":"#ffffff","header_top_pattern":"","header_bottom_font_size":"14px","header_bottom_backcolor":"#ffffff","header_bottom_color":"#3a3a3a","header_bottom_menu_hover_color":"#ffaa00","header_bottom_link_color":"#8db538","header_bottom_hoverlink_color":"#ffaa00","header_bottom_division_color":"#ffffff","header_bottom_border_color":"#e0e0e0","header_bottom_pattern":"","left_font_size":"13px","left_backcolor":"#ffffff","left_heading_color":"#000000","left_color":"#565656","left_border_color_line":"#ffffff","left_border_color":"#c1c1c1","left_widget_backcolor":"#ffffff","left_widget_margin":"0px","left_border_color_menu":"#e8e8e8","left_link_color":"#8db538","left_hoverlink_color":"#ffaa00","left_pattern":"","left_pattern_scroll":"fixed","main_font_size":"14px","main_meta_font_size":"11px","main_backcolor":"#ffffff","main_heading_color":"#0a0a0a","main_color":"#565656","main_hig_backcolor":"#0c0c0c","main_hig_color":"#ffffff","main_hig_hover_color":"#ffaa00","main_alt_backcolor":"#ffffff","main_alt_color":"#ffaa00","main_window_border_color":"#ffffff","main_border_color":"#ffffff","main_link_color":"#8db538","main_hoverlink_color":"#ffaa00","main_link_back_color":"#ffffff","main_pattern":"","main_pattern_scroll":"fixed","right_font_size":"13px","right_backcolor":"#ffffff","right_heading_color":"#000000","right_color":"#515151","right_border_color_line":"#ffffff","right_border_color":"#c1c1c1","right_widget_backcolor":"#ffffff","right_widget_margin":"0px","right_border_color_menu":"#ededed","right_link_color":"#8db538","right_hoverlink_color":"#ffaa00","right_pattern":"","right_pattern_scroll":"scroll","footer_font_size":"13px","footer_backcolor":"#ffffff","footer_heading_color":"#0a0a0a","footer_color":"#565656","footer_border_color_line":"#e0e0e0","footer_border_color":"#ffffff","footer_border_color_menu":"#e0e0e0","footer_link_color":"#8db538","footer_hoverlink_color":"#ffaa00","footer_pattern":"","footer_pattern_scroll":"scroll","socket_font_size":"11px","socket_backcolor":"#ffffff","socket_color":"#565656","socket_division_color":"#ffffff","socket_border_color":"#e0e0e0","socket_link_color":"#8db538","socket_hoverlink_color":"#ffaa00","socket_pattern":"","socket_pattern_scroll":"fixed"}',
    'FRAC BLACK' => '{"heading_font":"PT Sans Narrow","body_font":"Advent Pro","blog_title_font":"Allerta","general_backcolor":"#0a0a0a","general_pattern":"","general_pattern_scroll":"fixed","font_size_h1":"36px","font_size_h2":"28px","font_size_h3":"20px","font_size_h4":"16px","font_size_h5":"13px","font_size_h6":"11px","header_top_font_size":"11px","header_top_backcolor":"#000000","header_top_color":"#afafaf","header_top_menu_hover_color":"#ffffff","header_top_link_color":"#ffcc00","header_top_hoverlink_color":"#ffa100","header_top_division_color":"#444444","header_top_pattern":"","header_bottom_font_size":"13px","header_bottom_backcolor":"#000000","header_bottom_color":"#d1d1d1","header_bottom_menu_hover_color":"#ffffff","header_bottom_link_color":"#ffcc00","header_bottom_hoverlink_color":"#ffa100","header_bottom_division_color":"#444444","header_bottom_border_color":"#444444","header_bottom_pattern":"horizontal4_black_t","left_font_size":"13px","left_backcolor":"#000000","left_heading_color":"#f4f4f4","left_color":"#a5a5a5","left_border_color_line":"#000000","left_border_color":"#515151","left_widget_backcolor":"transparent","left_widget_margin":"15px","left_border_color_menu":"#444444","left_link_color":"#ffcc00","left_hoverlink_color":"#ffa100","left_pattern":"vertical3_black_t","left_pattern_scroll":"fixed","main_font_size":"14px","main_meta_font_size":"11px","main_backcolor":"#000000","main_heading_color":"#ffcc00","main_color":"#aaaaaa","main_hig_backcolor":"#000000","main_hig_color":"#ffcc00","main_hig_hover_color":"#ffffff","main_alt_backcolor":"#3d3d3d","main_alt_color":"#898989","main_window_border_color":"#000000","main_border_color":"#444444","main_link_color":"#ffcc00","main_hoverlink_color":"#ffa100","main_link_back_color":"#262626","main_pattern":"","main_pattern_scroll":"fixed","right_font_size":"13px","right_backcolor":"#000000","right_heading_color":"#f4f4f4","right_color":"#a5a5a5","right_border_color_line":"#000000","right_border_color":"#515151","right_widget_backcolor":"transparent","right_widget_margin":"15px","right_border_color_menu":"#444444","right_link_color":"#ffcc00","right_hoverlink_color":"#ffa100","right_pattern":"vertical3_black_t","right_pattern_scroll":"fixed","footer_font_size":"13px","footer_backcolor":"#000000","footer_heading_color":"#ffffff","footer_color":"#bababa","footer_border_color_line":"#353535","footer_border_color":"#666666","footer_border_color_menu":"#3d3d3d","footer_link_color":"#ffcc00","footer_hoverlink_color":"#ffa100","footer_pattern":"horizontal4_black_t","footer_pattern_scroll":"scroll","socket_font_size":"11px","socket_backcolor":"#000000","socket_color":"#a3a3a3","socket_division_color":"#3d3d3d","socket_border_color":"#000000","socket_link_color":"#ffcc00","socket_hoverlink_color":"#ffa100","socket_pattern":"","socket_pattern_scroll":"fixed"}'
);