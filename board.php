<?php
class TicTacToeBoard {
    const CELL_EMPTY = 0;
    const CELL_PLAYER_1 = 1;
    const CELL_PLAYER_2 = 2;

    // Cell indices go left to right, then down:
    //  0 | 1 | 2
    // ---+---+---
    //  3 | 4 | 5
    // ---+---+---
    /// 6 | 7 | 8
    private $cells = array();

    // The "direction" is the increment between cells on that diagonal.
    // For example, to go southeast from 1 to 5, you increment by 4 (1 + 4 = 5).
    const DIRECTION_EAST = 1;
    const DIRECTION_SOUTHEAST = 4;
    const DIRECTION_SOUTH = 3;
    const DIRECTION_NORTHEAST = -2;

    public function TicTacToeBoard() {
    }

    public function init() {
        $this->cells = array_fill(0, 9, self::CELL_EMPTY);
    }

    public function get(int $cell) {
        self::validate_cell($cell);

        return $this->cells[$cell];
    }

    public function set(int $player, int $cell) {
        self::validate_cell($cell);

        $this->cells[$cell] = $player;
    }

    public function did_win(int $player, int $cell) {
        self::validate_cell($cell);

        if ($this->did_win_in_direction($player, $cell, self::DIRECTION_EAST)) {return true;}
        elseif ($this->did_win_in_direction($player, $cell, self::DIRECTION_SOUTHEAST)) {return true;}
        elseif ($this->did_win_in_direction($player, $cell, self::DIRECTION_SOUTH)) {return true;}
        elseif ($this->did_win_in_direction($player, $cell, self::DIRECTION_NORTHEAST)) {return true;}
        else {return false;}
    }

    private static function validate_cell(int $cell) {
        if ($cell < 0 || $cell >= 9) {
            throw new Exception('Cell is out of the range [0, 9)');
        }
    }

    private static function get_cell_indices_in_direction(int $origin, int $direction) {
        if ($direction === self::DIRECTION_SOUTH) {
            // Move origin to the top row
            $origin %= 3;
        } else {
            // Move origin to the left column
            $origin -= ($origin % 3) * $direction;
        }

        $res = array($origin, $origin + $direction, $origin + $direction * 2);
        if ($res[0] >= 0 && $res[2] < 9) {
            return $res;
        } else {
            return null;
        }
    }

    private function did_win_in_direction(int $player, int $origin, int $direction) {
        $cell_indices = self::get_cell_indices_in_direction($origin, $direction);
        if ($cell_indices === null) {return false;}

        $cell_values = array_intersect_key($this->cells, $cell_indices);
        return $cell_values[0] === $player
            && $cell_values[1] === $player
            && $cell_values[2] === $player;
    }
}
