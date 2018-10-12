// Adds function to some prototypes that are required across the entire application.

/**
 * Returns the normalized version of a string. The normalized version means:
 * - Lowercase.
 * - No accents.
 */
String.prototype.normalized = function() {
    return this.normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase();
}

/**
 * Returns true if the string contains another given string inside it.
 */
String.prototype.matches = function(other) {
    return this.normalized().includes(other.toString().normalized());
};

Array.prototype.getPositionById = function(id, attr) {
    attr = attr ? attr : 'id';
    let i = 0;
    while(i < this.length && this[i][attr] != id) {
        i++;
    }
    return i === this.length ? undefined : i;
}

Array.prototype.getPositionsByIds = function(ids, attr) {
    attr = attr ? attr : 'id';    
    let i;
    let ret = [];
    ids.forEach(id => {
        i = this.getPositionById(id, attr);
        if(i !== undefined) { ret.push(i) }
    });
    return ret;
}

Array.prototype.getById = function(id, attr) {
    attr = attr ? attr : 'id';
    let i = this.getPositionById(id, attr);
    return i === this.length ? undefined : this[i];
}

Array.prototype.getByIds = function(ids, attr) {
    attr = attr ? attr : 'id';
    let ob;
    let ret = [];
    ids.forEach(id => {
        ob = this.getById(id, attr);
        if( i !== undefined) { ret.push(ob) }
    });
    return ret;
}
