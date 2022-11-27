# Code guidelines

## General 

Use camelCase when writing code and naming files<br>
Examples for camelCase: 
* $variableOne = "foo";
* functionDoSomething();
* exampleFileName.php

Indentation: 3 spaces<br>
Charset: UTF-8

Make sure to leave an empty new line at the end of every page!

# HTML & PHP

## HTML

### Rules
Make sure that you follow the rules and add all required attributes 
for example:
```<img src="" alt="">```

### Validate your file befor pushing

* Use the following validator to validate your HTML: https://validator.w3.org/#validate_by_input

* Use the following validator to validate your CSS:  https://jigsaw.w3.org/css-validator/#validate_by_input

### Comments in HTML code

Stick to the following scheme for the sections

``` HTML
<main>
   <!-- heroBanner --> 
   <section class="heroBanner">

   </section>
   <!-- findUs -->
   <section class="findUs">
      
   </section>
   <p>TESTI TEST</p>
</main>
```
 
## PHP

TODO 

## CSS

All CSS must be in the styles/styles.css file
* Follow the scheme for the style.css file
* Our CSS is in groups, we have **media queries**, **HTML tags**, **classes**, **IDs**

### CSS code example
```CSS
/* Media queries */
@font-face {
   font-family: CircularStd-Black;
   src: url(fonts/CircularStd/CircularStd-Black.otf);
}

/* HTML Tags */
header {
   background-color: blueviolet;
   grid-column: 2/7;
}

main {
   background-color: aqua;
   grid-column: 2/7;
}

footer {
   background-color: yellowgreen;
   grid-column: 2/7;
}

/* Classes */
.gridContainer {
   display: grid;
   grid-template-columns: repeat(7,1fr);
}

/* IDs */
#logo {
   height: 300px;
}
```
