/**
 * Creates an HTML element
 * @param {*} tag     Element type
 * @param {*} options { text: <text_content>, class: <class_name>, html: <html_content>}
 * @returns The element
 * @author ChatGPT-4o
 */
export const createElement = (tag, options = {}) => {
    const el = document.createElement(tag);
    if (options.text) el.textContent = options.text;
    if (options.class) el.className = options.class;
    if (options.html) el.innerHTML = options.html;
    return el;
};