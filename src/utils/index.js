export const round = (num) => {
    return Math.round(num * 100)/100;
}

export const updateOrNew = (collection, item, matchProp) => {
    collection = [...collection];
    item = {...item};
    
    const find = collection.filter(v => v[matchProp] === item[matchProp]).length > 0;
    
    if(find){
        collection = collection.map(v => {
            return v[matchProp] === item[matchProp] ? item : v;
        });
        return collection;
    }
    
    collection.push(item);
    
    return collection;
}