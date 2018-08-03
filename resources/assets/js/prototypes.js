String.prototype.matches = function(other) { 
    other = typeof other === 'String' ? other : other.toString();
    return this.toUpperCase().includes(other.toUpperCase())
};

Array.prototype.getPositionById = function(id) {
    let i = 0;
    while(i < this.length && this[i].id !== id) {
        i++;
    }
    return i === this.length ? undefined : i;
}

Array.prototype.getPositionsByIds = function(ids) {
    let i;
    let ret = [];
    ids.forEach(id => {
        i = this.getPositionById(id);
        if(i !== undefined) { ret.push(i) }
    });
    return ret;
}

Array.prototype.getById = function(id) {
    let i = this.getPositionById(id);
    return i === this.length ? undefined : this[i];
}

Array.prototype.getByIds = function(ids) {
    let ob;
    let ret = [];
    ids.forEach(id => {
        ob = this.getById(id);
        if( i !== undefined) { ret.push(ob) }
    });
    return ret;
}
