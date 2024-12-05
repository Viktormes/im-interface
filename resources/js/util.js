function formatString(format, ...args) {
    const matchCode = function (code, val) {
        switch (code) {
            case 'ddd': {
                const v = Math.floor(val / 86400);
                const y = v < 100 ? '0' : '';
                const z = v < 10 ? '0' : '';
                return `${y}${z}${v}`;
            }
            case 'dd': {
                const v = Math.floor(val / 86400);
                const z = v < 10 ? '0' : '';
                return `${z}${v}`;
            }
            case 'd': {
                const v = Math.floor(val / 86400);
                return `${v}`;
            }
            case 'hh': {
                const v = Math.floor((val % 86400) / 3600);
                const z = v < 10 ? '0' : '';
                return `${z}${v}`;
            }
            case 'h': {
                const v = Math.floor((val % 86400) / 3600);
                return `${v}`;
            }
            case 'mm': {
                const v = Math.floor((val % 3600) / 60);
                const z = v < 10 ? '0' : '';
                return `${z}${v}`;
            }
            case 'm': {
                const v = Math.floor((val % 3600) / 60);
                return `${v}`;
            }
            case 'ss': {
                const v = val % 60;
                const z = v < 10 ? '0' : '';
                return `${z}${v}`;
            }
            case 's': {
                const v = val % 60;
                return `${v}`;
            }
        }

        return null;
    }

    const formatCustom = function (format, arg) {
        return format.replace(/\[(.*?)]|('.*?')|([A-Z]+)/ig, (full, brackets, str, code) => {
            const neg = +arg < 0;
            const val = Math.round(Math.abs(+arg));

            if (brackets) {
                if (brackets === '-') {
                    return neg ? '-' : '';
                }

                let failed = false;
                const bracketResult = brackets.replace(/('.*?')|([A-Z]+)/ig, (f, s, c) => {
                    if (s) {
                        return s.substring(1, s.length - 1);
                    }

                    const cr = matchCode(c, val);
                    if (+cr === 0) {
                        failed = true;
                        return '';
                    }

                    return cr;
                });

                return failed ? '' : bracketResult;
            }

            if (str) {
                return str.substring(1, str.length - 1);
            }

            const result = matchCode(code, val);
            if (result !== null) return result;

            return '';
        });
    }

    return format.replace(/\{(\d+)(?::([A-Z':.\-_\[\] ]+)(\d+)?)?}/ig, (full, index, type, precision) => {
        const upperType = type ? type.toUpperCase() : type;

        const defaultPrecision = 2;

        switch (upperType) {
            case 'F':
            case 'N':
                return (+args[index]).toFixed(precision || defaultPrecision);
            case 'P':
                return (+args[index] * 100).toFixed(precision || defaultPrecision) + ' %';
            case 'B':
                return (+args[index]).toString(2);
            case 'X':
                const hex = (+args[index]).toString(16);
                return type === 'x' ? hex : hex.toUpperCase();
            case 'E':
                const exp = (+args[index]).toExponential(precision || 6);
                return type === 'e' ? exp : exp.toUpperCase();
            case 'C':
                const neg = +args[index] < 0;
                const v = Math.round(Math.abs(+args[index]));
                const days = Math.floor(v / 86400);
                const hours = Math.floor((v % 86400) / 3600);
                const minutes = Math.floor((v % 3600) / 60);
                const seconds = v % 60;

                let result = neg ? '-' : '';
                let hit = false;
                if (days > 0) {
                    result += `${days}.`;
                    hit = true;
                }
                if (hours > 0 || hit) {
                    const z = hours < 10 ? '0' : '';
                    result += `${z}${hours}:`;
                    hit = true;
                }
                if (minutes > 0 || hit) {
                    const z = minutes < 10 ? '0' : '';
                    result += `${z}${minutes}:`;
                    hit = true;
                }
                if (seconds > 0 || hit) {
                    const z = seconds < 10 ? '0' : '';
                    result += `${z}${seconds}`;
                }

                return result;
        }

        if (type) {
            return formatCustom(type, args[index]);
        }

        return args[index];
    });
}

export {
    formatString
};
