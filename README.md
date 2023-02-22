## Convert validation rules to input attributes

Given the following validation rule:

```
'username' => ['required', 'string', 'min:3', 'max:64',
                'regex:/^[a-z]([a-z0-9]|[a-z0-9]\.[a-z0-9])*$/i']
```

It can be converted to the following input with its attributes:

```
    <input 
        name="username" 
        required="required" 
        type="text" 
        minlength="3" 
        maxlength="64" 
        pattern="^[a-z]([a-z0-9]|[a-z0-9]\.[a-z0-9])*$" 
    >
```

Visiting route ‘/signup’ get, it is possible to see the result of applying this concept.

Please visit the following link if you will like to give me feedback. I’m a newbie laravel and OOp programer, and any comment on how I could improved will be very much appreciated.

https://laracasts.com/discuss/channels/code-review/transform-validation-rules-into-inputs-with-their-attributes

If my code is a little hard to read, please check my tests and commit sequence, were I try to justify the purpose of every line in my code. Especially BlueprintsFactoryTest.

Copyright (c) 2023, Ariel Del Valle Lozano <arielenterdev@gmail.com>

GNU General Public License (GPL) version 3 http://www.gnu.org/licenses/gpl-3.0.html