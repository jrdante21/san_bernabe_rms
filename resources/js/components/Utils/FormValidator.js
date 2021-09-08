import validator from 'validator'

export default function FormValidator () {

    const validate = (v, label='Field') => {

        return {
            date: function (opts={}) {
                let err = []
                let isReq = (typeof opts.isReq === 'undefined') ? true : opts.isReq
                if (!isReq) return err

                if (!validator.isDate(v)) err.push(label+" is not a date format.")
                return err
            },

            alpha: function (opts={}) {
                v = String(v)

                let min = (opts.min) ? opts.min : 2
                let isReq = (typeof opts.isReq === 'undefined') ? true : opts.isReq
                let hasNum = (typeof opts.hasNum === 'undefined') ? false : opts.hasNum
                let err = []
                if (isReq && validator.isEmpty(v)) {
                    err.push(label+" is required.")
                    return err
                }
                if (!isReq && validator.isEmpty(v)) return err

                if (hasNum) {
                    if (!validator.isAlphanumeric(v, 'en-US', {ignore:'\s-\.\,'})) err.push(label+" must contain letters, numbers, spaces, or '-' only.")
                } else {
                    if (!validator.isAlpha(v, 'en-US', {ignore:'\s-\.'})) err.push(label+" must contain letters, spaces, or '-' only.")
                }

                if (!validator.isLength(v, {min:min})) err.push(label+" must have atleast "+min+" characters.")
                return err
            },

            username: function (opts={}) {
                v = String(v)
                let min = (opts.min) ? opts.min : 2
                let isReq = (typeof opts.isReq === 'undefined') ? true : opts.isReq
                let err = []
                if (isReq && validator.isEmpty(v)) {
                    err.push(label+" is required.")
                    return err
                }
                if (!isReq && validator.isEmpty(v)) return err
                if (!validator.isAlphanumeric(v)) err.push(label+" must contain letters and numbers only.")
                if (!validator.isLength(v, {min:min})) err.push(label+" must have atleast "+min+" characters.")
                return err
            },

            numeric: function () {
                v = String(v)
                
                let err = []
                if (validator.isEmpty(v)) {
                    err.push(label+" is required.")
                    return err
                }
                if (!validator.isNumeric(v)) err.push(label+" must contain numbers only.")
                return err
            },

            json: function () {
                let err = []
                if (validator.isEmpty(v)) {
                    err.push(label+" is required.")
                    return err
                }
                if (!validator.isJSON(v)) err.push(label+" must be a json value.")
                return err
            }
        }
    }

    return { validate }
}