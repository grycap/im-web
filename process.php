<?php
/*
 IM - Infrastructure Manager
 Copyright (C) 2011 - GRyCAP - Universitat Politecnica de Valencia

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class Process{
    private $pid;
    private $command;
    private $output_file;

    public function __construct($cl=false, $out_file=false){
        if ($cl != false){
            $this->command = $cl;
        }
	if ($out_file != false) {
            $this->output_file = $out_file;
	} else {
            $this->output_file = tempnam("/tmp", "ec3_");
            unlink($this->output_file);
	}
    }
    private function runCom(){
        $command = 'nohup ' . $this->command . ' > ' . $this->output_file . ' 2>&1 & echo $!';
        exec($command ,$op);
        $this->pid = (int)$op[0];
	return $this->pid;
    }

    public function getOutput(){
	if (is_file($this->output_file)) return file_get_contents($this->output_file);
	else return '';
    }

    public function setPid($pid){
        $this->pid = (int)$pid;
    }

    public function getPid(){
        return $this->pid;
    }

    public function status(){
        $command = '/bin/ps -p '.$this->pid;
        exec($command,$op);
        if (!isset($op[1])) {
		return false;
	} else {
        	return true;
	}
    }

    public function start(){
	$res = NULL;
        if ($this->command != '') $res = $this->runCom();
        return $res;
    }

    public function stop(){
        $command = 'kill '.$this->pid;
        exec($command);
        if ($this->status() == false)return true;
        else return false;
    }
}
?>
