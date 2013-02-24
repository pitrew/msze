/**
 * jquery.timer.js
 *
 * Copyright (c) 2011 Jason Chavannes <jason.chavannes@gmail.com>
 *
 * http://jchavannes.com/jquery-timer
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of self software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use, copy,
 * modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and self permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
 * BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN
 * ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 *
 */

(function($) {
	$.timer = function(func, time, autostart) {	
        var self = this;

        var _active = false;
        var _init = null;
        var _action = null;
        var _intervalTime = null;
        var _remaining = 0;
        var _last = 0;
        var _timeoutObject = null;

        self.set = function(func, time, autostart) {
	 		_init = true;
	 	 	if(typeof func == 'object') {
		 	 	var paramList = ['autostart', 'time'];
	 	 	 	for(var arg in paramList) {if(func[paramList[arg]] != undefined) {eval(paramList[arg] + " = func[paramList[arg]]");}};
 	 			func = func.action;
	 	 	}
	 	 	if(typeof func == 'function') {_action = func;}
		 	if(!isNaN(time)) {_intervalTime = time;}
		 	if(autostart && !_active) {
			 	_active = true;
			 	self.setTimer();
		 	}
		 	return self;
	 	};
	 	self.once = function(time) {
	 	 	if(isNaN(time)) {time = 0;}
			window.setTimeout(function() {_action();}, time);
	 		return self;
	 	};
		self.play = function(reset) {
    		if(!_active) {
				if(reset) {self.setTimer();}
				else {self.setTimer(_remaining);}
				_active = true;
			}
			return self;
		};
		self.pause = function() {
			if(_active) {
    			_active = false;
				_remaining -= new Date() - _last;
				self.clearTimer();
			}
			return self;
		};
		self.stop = function() {
			_active = false;
			_remaining = _intervalTime;
			self.clearTimer();
			return self;
		};
		self.toggle = function(reset) {
			if(_active) {self.pause();}
			else if(reset) {self.play(true);}
			else {self.play();}
			return self;
		};
		self.reset = function() {
			_active = false;
			self.play(true);
			return self;
		};
		self.clearTimer = function() {
            if (_timeoutObject != null) {
			    window.clearTimeout(_timeoutObject);
                _timeoutObject = null;
            }
		};
	 	self.setTimer = function(time) {
	 	 	if(typeof _action != 'function') {return;}
	 	 	if(isNaN(time)) {time = _intervalTime;}
		 	_remaining = time;
	 	 	_last = new Date();
			self.clearTimer();
			_timeoutObject = window.setTimeout(function() {self.go();}, time);
		};
	 	self.go = function() {
	 		if(_active) {
	 			_action();
	 			self.setTimer();
	 		}
	 	};
	 	
	 	if(_init) {
	 		return new $.timer(func, time, autostart);
	 	} else {
			self.set(func, time, autostart);
	 		return self;
	 	}
	}
})(jQuery);