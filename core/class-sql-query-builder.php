<?php

abstract class GRE_SQL_Query_Builder {

    protected $db;

    protected $clauses = array();

    public function __construct( $db ) {
        $this->db = $db;
    }

    public function get_sql( $params ) {
        $select = $this->build_select_clause( $params );
        $where = $this->build_where_clause( $params );
        $limit = $this->build_limit_clause( $params );
        $order = $this->build_order_clause( $params );

        if ( $params['fields'] == 'count' ) {
            return $this->prepare_sql( "$select $where $order" );
        } else {
            return $this->prepare_sql( "$select $where $order $limit" );
        }
    }

    protected abstract function build_select_clause( $params );
    protected abstract function build_where_clause( $params );
    protected abstract function build_limit_clause( $params );
    protected abstract function build_order_clause( $params );

    protected function prepare_sql( $sql ) {
        if ( ! empty( $this->clauses['join'] ) ) {
            $sql = str_replace( '<join>', implode( ' ', $this->clauses['join'] ), $sql );
        } else {
            $sql = str_replace( '<join>', '', $sql );
        }

        return $sql;
    }

    protected function add_join_clause( $clause ) {
        $this->clauses['join'][] = $clause;
    }

    protected function get_meta_sql( $name, $meta_query, $type, $primary_table, $primary_id_column ) {
        $query = get_meta_sql( $meta_query, $type, $primary_table, $primary_id_column );

        if ( function_exists( '_get_meta_table' ) ) {
            $meta_table = _get_meta_table( $type );

            $query['join'] = str_replace( $meta_table, $name, $query['join'] );
            $query['where'] = str_replace( $meta_table, $name, $query['where'] );

            $query['join'] = str_replace( "JOIN $name ON" , "JOIN $meta_table AS $name ON", $query['join'] );
        }

        return array(
            'join' => $query['join'],
            'where' => $this->clean_meta_sql_conditions( $query['where'] ),
        );
    }

    private function clean_meta_sql_conditions( $condition ) {
        $condition = preg_replace( "/(?:^ AND )|\n|\t/", '', $condition );
        $condition = preg_replace( '/\(\s*\(/', '(', $condition );
        $condition = preg_replace( '/\)\s*\)/', ')', $condition );

        return $condition;
    }

    protected function build_simple_condition( $params, $param_name, $condition ) {
        if ( isset( $params[ $param_name ] ) ) {
            return $this->db->prepare( $condition, $params[ $param_name ] );
        } else {
            return null;
        }
    }

    protected function group_conditions( $conditions, $connector = 'OR' ) {
        $conditions_count = count( $conditions );

        if ( is_array( $conditions ) && $conditions_count >= 1 ) {
            if ( $conditions_count > 1 ) {
                return '( ' . implode( " $connector ", $conditions ) . ' )';
            } else if ( $conditions_count == 1 ) {
                return array_pop( $conditions );
            }
        } else if ( ! is_array( $conditions ) ) {
            return $conditions;
        } else {
            return '';
        }
    }

    protected function build_condition_with_in_clause( $column, $value, $placeholder = '%d' ) {
        return $this->build_condition_with_inclusion_operators( $column, $value, 'IN', '=', $placeholder );
    }

    protected function build_condition_with_inclusion_operators( $column, $value, $inclusion_operator, $comparison_operator, $placeholder ) {
        if ( is_array( $value ) && ! empty( $value ) ) {
            if ( count( $value ) == 1 ) {
                $single_value = array_shift( $value );
                return $this->db->prepare( "$column $comparison_operator $placeholder", $single_value );
            } else {
                $multiple_values = array();

                foreach ( $value as $v ) {
                    $multiple_values[] = $this->db->prepare( "$placeholder", $v );
                }

                return "$column $inclusion_operator ( " . implode( ', ', $multiple_values ) . ' )';
            }
        } else if ( ! empty( $value ) ) {
            return $this->db->prepare( "$column $comparison_operator $placeholder", $value );
        } else {
            return '';
        }
    }
}
