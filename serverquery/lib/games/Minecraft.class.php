<?php

/*
 * The MIT License
 *
 * Copyright 2015 Steve Guidetti.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace SQ\Game;

/**
 * Game: Minecraft
 *
 * @author Steve Guidetti
 */
class Minecraft extends \SQ\Gameserver {

    protected $defaultConfig = array(
        /**
         * @var bool Use the Query protocol (server must set enable-query=true)
         */
        'useQuery' => false,
        /**
         * @var int Port used by the Query protocol (server query.port property)
         */
        'queryPort' => 25565,
        /**
         * @var bool Use the pre-1.7 Server List Ping protocol (overrides useQuery)
         */
        'useLegacy' => false,
    );
    protected $port = 25565;

    public function setName($name) {
        // strip color and formatting codes
        $name = preg_replace('/\xC2|\xA7./', '', $name);
        parent::setName($name);
    }

    protected function query($timeout) {
        if($this->config['useLegacy']) {
            if(!class_exists('SQ\Game\MinecraftLegacy')) {
                require __DIR__ . '/inc/MinecraftLegacy.class.php';
            }
            $obj = new MinecraftLegacy($this);
        } elseif($this->config['useQuery']) {
            if(!class_exists('SQ\Game\MinecraftQuery')) {
                require __DIR__ . '/inc/MinecraftQuery.class.php';
            }
            $obj = new MinecraftQuery($this, $this->config['queryPort']);
        } else {
            if(!class_exists('SQ\Game\MinecraftSLP')) {
                require __DIR__ . '/inc/MinecraftSLP.class.php';
            }
            $obj = new MinecraftSLP($this);
        }

        $obj->query($timeout);
    }

}
