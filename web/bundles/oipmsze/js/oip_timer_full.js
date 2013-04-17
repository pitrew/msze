function oip_timer(clb) {
    this._StateEnum = {
        NOT_STARTED: 'not_started',
        ACTIVE: 'active',
        PAUSED: 'paused',
        INACTIVE: 'inactive'
    }
    var self = this,
    _main_timerCallback = clb,
    _run_after = 0,
    _run_every = 0,
    _repeat = 0,
    _main_timer = null,
    _firstTime = true,
    _state = self._StateEnum.NOT_STARTED,
    _setState = function (newState) {
        if (newState == self._StateEnum.INACTIVE) {
            _main_timer.stop();
        }
       _state = newState;
    },

    _internalClb = function () {
        if (_main_timerCallback != null) {
            _main_timerCallback();

            if (_firstTime == true) {
                _firstTime = false;
                if (_repeat > 0) { //if repeat was specified
                    _repeat--;
                    if (_repeat > 0 && _run_every > 0) {
                        if (_state != self._StateEnum.PAUSED) {
                            _main_timer.set(_internalClb, _run_every, true);
                        } else {
                            _main_timer.set(_internalClb, _run_every, false);
                        }
                    } else {
                        _setState(self._StateEnum.INACTIVE);
                    }
                } else { //without repeat
                    if (_run_every > 0) {
                        if (_state != self._StateEnum.PAUSED) {
                            _main_timer.set(_internalClb, _run_every, true);
                        } else {
                            _main_timer.set(_internalClb, _run_every, false);
                        }
                    } else {
                        _setState(self._StateEnum.INACTIVE);
                    }
                }
            } else {
                if (_repeat > 0) {
                    _repeat--;
                    if (_repeat == 0) {
                        _main_timer.stop();
                        _setState(self._StateEnum.INACTIVE);
                    }
                }
            }
        }
    };

    self.oip_timer = function () {
        _main_timer = new $.timer(_internalClb);
    }

    //
    // 1). Run once (after 1s): run_after=1000
    // 2). Run twice (after 1s and then after 1.5s): run_after=1000 run_every=1500 repeat=2
    // 3). Run 3 times (every 1s): run_after=0 run_every=1000 repeat=3
    // 4). Run every 1s after 3s: run_after=3000 run_every=1000
    //
    self.setup = function (run_after_arg, run_every_arg, repeat_arg) {
        if (run_after_arg == undefined) { run_after_arg = 0; run_every_arg = 0; repeat_arg = 0; }
        if (run_every_arg == undefined) { run_every_arg = 0; repeat_arg = 0; }
        if (repeat_arg == undefined) { repeat_arg = 0; }

        if (_state == self._StateEnum.INACTIVE || run_after_arg != _run_after || run_every_arg != _run_every) {
            _main_timer.stop();

            if ((run_after_arg != null && run_after_arg != 0) || (run_every_arg != null && run_every_arg != 0)) {
                _run_after = run_after_arg;
                _run_every = run_every_arg;
                _repeat = repeat_arg;
                _setState(self._StateEnum.ACTIVE);

                _firstTime = true;
                if (_run_after == 0) {
                    _main_timer.set(_internalClb, _run_every, true);
                } else {
                    _main_timer.set(_internalClb, _run_after, true);
                }
            } else {
                _setState(self._StateEnum.INACTIVE);
            }
        }
    }

    self.stop = function () {
        self.setup();
    }

    self.is_terminated = function () {
        return _state == self._StateEnum.INACTIVE;
    }

    self.is_active = function () {
        return _state == self._StateEnum.ACTIVE;
    }

    self.pause = function () {
        if (self._StateEnum.ACTIVE == _state) {
            _main_timer.pause();
            _setState(self._StateEnum.PAUSED);
        }
    }

    self.resume = function () {
        if (self._StateEnum.PAUSED == _state) {
            _setState(self._StateEnum.ACTIVE);
            _main_timer.play(false);
        }
    }

    self.oip_timer();
};
