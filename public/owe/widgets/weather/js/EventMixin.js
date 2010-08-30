/**
 * Generic mixin to give classes event dispatching abilities
 * Works more or less like normal dom events
 */
function EventMixin(t) {
    t._eventListeners = {};


    /**
     * Add an event listener for a given event.
     * @argument name {String} The name of the event to register
     * @argument callback {Function} The function to call when event is raised
     */
    t.addEventListener = function(name, callback)
    {
        if (name in this._eventListeners)
        {
            this._eventListeners[name].push(callback);
        }
        else
        {
            this._eventListeners[name] = [callback]
        }
    }

    /**
     * Remove a listener for a given event.
     * @argument name {String} The name of the event to register
     * @argument callback {Function} The function to call when event is raised
     */
    t.removeEventListener = function(name, callback)
    {
        if (!(name in this._eventListeners)) {return};
        var listeners = this._eventListeners[name];
        for (var n=0, e; e=listeners[n]; n++)
        {
            if (e==callback) { listeners.splice(n, 1); }
        }
    }

    /**
     * Dispatch an event
     * Internal
     */
    t._dispatchEvent = function(name, payload)
    {
        if (!(name in this._eventListeners)) { return }
        var handlers = this._eventListeners[name];
        for (var n=0, e; e=handlers[n]; n++)
        {
            e(payload);
        }
    }
}
