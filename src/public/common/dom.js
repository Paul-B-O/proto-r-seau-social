"use strict";

export const $ = (first, second) => {
    if (typeof first === "string") return document.querySelector(first);
    else if (second != null) return first.querySelector(second);
    return first;
};

export const $$ = (first, second) => {
    if (typeof first === "string") return document.querySelectorAll(first);
    else return first.querySelectorAll(second);
};

export function $make(tagName, parent, props) {
    const elt = document.createElement(tagName);
    if (parent != null) parent.appendChild(elt);
    for (const key in props) {
        if (key === "dataset") {
            for (const dataKey in props[key]) elt.dataset[dataKey] = props.dataset[dataKey];
        } else elt[key] = props[key];
    }
    return elt;
}

export function $makeText(text, parent) {
    const node = document.createTextNode(text);
    if (parent != null) parent.appendChild(node);
    return node;
}

export function $show(elt, visible) { $(elt).hidden = visible != null ? !visible : false; }
export function $hide(elt) { $(elt).hidden = true; }
