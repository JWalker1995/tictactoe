<?php
require('board.php');

class TicTacToeController {
    const RESULT_DRAW = TicTacToeBoard::CELL_EMPTY;
    const RESULT_PLAYER_1 = TicTacToeBoard::CELL_PLAYER_1;
    const RESULT_PLAYER_2 = TicTacToeBoard::CELL_PLAYER_2;

    private $wins = array(0, 0, 0);

    private $result;

    public function run() {
        $board = new TicTacToeBoard();
        $board->init();

        $this->result = $this->do_turn($board, TicTacToeBoard::CELL_PLAYER_1);
    }

    public function print_results() {
        $total = array_sum($this->wins);

        echo 'Playing randomly:' . PHP_EOL;
        echo '    ' . number_format($this->wins[0] / $total, 2) . '% of games resulted in a draw' . PHP_EOL;
        echo '    ' . number_format($this->wins[1] / $total, 2) . '% of games resulted in a win for player 1' . PHP_EOL;
        echo '    ' . number_format($this->wins[2] / $total, 2) . '% of games resulted in a win for player 2' . PHP_EOL;
        echo ($this->result ? 'Player ' . $this->result : 'Neither player') . ' is able to force a win' . PHP_EOL;
    }

    private function do_turn(TicTacToeBoard $board, int $player) {
        $can_force_win = array(false, false, false);

        for ($i = 0; $i < 9; $i++) {
            if ($board->get($i) !== TicTacToeBoard::CELL_EMPTY) {continue;}

            // Make move
            $board->set($player, $i);

            if ($board->did_win($player, $i)) {
                $this->wins[$player]++;
                $can_force_win[$player] = true;
            }

            // Recurse
            $res = $this->do_turn($board, self::get_opponent($player));
            $can_force_win[$res] = true;

            // Undo move
            $board->set(TicTacToeBoard::CELL_EMPTY, $i);
        }

        // If the current player can force a win, obviously he will
        if ($can_force_win[$player]) {return $player;}

        // Otherwise, if all moves result in the opponent being able to force a win, then he will
        if (!$can_force_win[self::RESULT_DRAW]) {
            if ($can_force_win[self::get_opponent($player)]) {return self::get_opponent($player);}
            else {
                // Draw
                $this->wins[self::RESULT_DRAW]++;
            }
        }

        // Otherwise, inconclusive
        return self::RESULT_DRAW;
    }

    private static function get_opponent(int $player) {
        return TicTacToeBoard::CELL_PLAYER_1 ^ TicTacToeBoard::CELL_PLAYER_2 ^ $player;
    }
}
