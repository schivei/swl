swl
===

A Simple Web Language Project

[![Build Status](https://travis-ci.org/schivei/swl.png)](https://travis-ci.org/schivei/swl)

#SWL Definitions
```java
// Numbers
byte     //  8 bits signed    -2^7  to 2^7  -1
ubyte    //  8 bits signed    0     to 2^8  -1
short    // 16 bits signed    -2^15 to 2^15 -1
ushort   // 16 bits unsigned  0     to 2^16 -1
int      // 32 bits signed    -2^31 to 2^31 -1
uint     // 32 bits unsigned  0     to 2^32 -1
long     // 64 bits signed    -2^63 to 2^63 -1
ulong    // 64 bits unsigned  0     to 2^64 -1
float    // same as int size   with floating point
ufloat   // same as uint size  with floating point
double   // same as long size  with floating point
udouble  // same as ulong size with floating point

// Magical
nozero::uint // to restrict a unsigned values to 1 or more

// Representations (casted to any other number types)
int a = 0x64       // Hexadecimal
int b = 0144       // Octal
int c = 01100100b  // Binary, left zeros are optional

// Boolean 1 bit (0 or 1), auto casting to number types
true
false

// Text
char     // same as ushort    '\u0000' to '\uffff'
string   // char[(2^31 -1)]   0 to 2^31 -1 (2147483647 chars)
"a#{myvar}c" // strings support a built-in expressions using '#{}'
             //like coffescript

<<<HEREDOC
HEREOC       // Heredoc support
<<<'NOWDOC'
'NOWDOC'     // Nowdoc support

// Lists
int[]         // a array of elements   0 to 2^31 -1 (2147483647 chars)
i.e.: int[] a = [1,2,3]

// Nullable
null          // 4 - 8 bits (32 and 64 bits architecture, respectively)
nullable::int // enable null on any types
int?          // alias to nullable::<type>
myvar??0      // coalesce alias

// Regexp
r/^(my[^ ]*)$/ // a regexp alias to new Regexp("/^(my[^ ]*)$/")
regexp a = r/^(my[^ ]*)$/i
a.isMatch 'mYr' // returns true

// Operators
// Aditive
1 + 1
a++
a += 1

1 - 1
a--
a -= 1

// Multiplicative
2 * 2
a *=  2
a**     // self multiply exponent (a = a * a)
a **= 3 // self multiply exponent (a = a * a * a)

4 / 2
a /= 2
a~~     // self divider square root (a = a \ a)
a ~~= 3 // self divider square root (a = a \ a \ a)
3 % 2
a%%
a %= 9

// With text
'z' * 2       // duplicate   => "zz"
"abc" + 2     // concatenate => "abc2"
'a' + 2 < 0   // pad left    => "0a"
'a' + 2 > 0   // pad right   => "a0"

// Bitwise
2 ^ 1     // XOR
2 !^ 1    // XNOR
a ^= 1
a !^= 1
2 & 1     // AND
2 !& 1    // NAND
a &= 1
a !&= 1
2 | 1     // OR
2 !| 1    // NOR
a |= 1
a !|= 1

01101b >> 1101b   // right shift
01101b >>> 1101b  // unsigned right shift
01101b << 1101b   // left shift
a >>= ~1101b      // assign the result

~   // unary operator to inverts a bit pattern
~a & 2

// Equality, Relation, Logical
1 == 1
'a' == 'a'
'b' is 'B'
3 instanceof Int32
1 != 2
1 not::is 2
'1' not::instanceof Int32
'a' < 'b'
'b' > 'a'
4 >= 3
0 <= '\u0' // compares with char
```
