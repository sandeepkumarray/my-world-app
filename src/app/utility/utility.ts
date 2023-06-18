import * as CryptoJS from 'crypto-js';
import { interval } from 'rxjs';
import { take } from 'rxjs/operators';

export class utility {
    static EncryptionKey: string = "b@^*!|)#E><L@@@$$$";

    static encrypt(value: string): string {
        let encryptionKey = CryptoJS.enc.Utf8.parse(this.EncryptionKey);
        let salt = CryptoJS.enc.Base64.parse('SXZhbiBNZWR2ZWRldg==');

        let iterations = 1000;
        let keyAndIv = CryptoJS.PBKDF2(encryptionKey, salt,
            {
                keySize: 256 / 32 + 128 / 32,
                iterations: iterations,
            });

        let hexKeyAndIv = CryptoJS.enc.Hex.stringify(keyAndIv);

        let key = CryptoJS.enc.Hex.parse(hexKeyAndIv.substring(0, 64));
        let iv = CryptoJS.enc.Hex.parse(hexKeyAndIv.substring(64, hexKeyAndIv.length));
        let encryptedStr = CryptoJS.AES.encrypt(CryptoJS.enc.Utf16LE.parse(value), key, { iv: iv }).toString();

        return encryptedStr;
    }

    static decrypt(value: string): string {
        let encryptionKey = CryptoJS.enc.Utf8.parse(this.EncryptionKey);
        let salt = CryptoJS.enc.Base64.parse('SXZhbiBNZWR2ZWRldg==');

        let iterations = 1000;
        let keyAndIv = CryptoJS.PBKDF2(encryptionKey, salt,
            {
                keySize: 256 / 32 + 128 / 32,
                iterations: iterations,
            });

        let hexKeyAndIv = CryptoJS.enc.Hex.stringify(keyAndIv);

        let key = CryptoJS.enc.Hex.parse(hexKeyAndIv.substring(0, 64));
        let iv = CryptoJS.enc.Hex.parse(hexKeyAndIv.substring(64, hexKeyAndIv.length));

        let decrypted = CryptoJS.AES.decrypt(value, key, { iv: iv });
        let decryptedStr = CryptoJS.enc.Utf16LE.stringify(decrypted);
        return decryptedStr;
    }

    static SizeSuffix(bytes: number, decimals = 2) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    static b64toBlob(b64Data: any, contentType = 'image/png', sliceSize = 512) {
        const byteCharacters = atob(b64Data);
        const byteArrays = [];

        for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            const slice = byteCharacters.slice(offset, offset + sliceSize);

            const byteNumbers = new Array(slice.length);
            for (let i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            const byteArray = new Uint8Array(byteNumbers);
            byteArrays.push(byteArray);
        }

        const blob = new Blob(byteArrays, { type: contentType });
        return blob;
    }

    static getRandom(arr: any[], n: number) {
        var result = new Array(n),
            len = arr.length,
            taken = new Array(len);
        if (n > len)
            throw new RangeError("getRandom: more elements taken than available");
        while (n--) {
            var x = Math.floor(Math.random() * len);
            result[n] = arr[x in taken ? taken[x] : x];
            taken[x] = --len in taken ? taken[len] : len;
        }
        return result;
    }

    static timeSince(date: Date) {

        var seconds = Math.floor((new Date().getTime() - date.getTime()) / 1000);

        var interval = seconds / 31536000;

        if (interval > 1) {
            return "about " + Math.floor(interval) + " years ago.";
        }
        interval = seconds / 2592000;
        if (interval > 1) {
            return "about " + Math.floor(interval) + " months ago.";
        }
        interval = seconds / 86400;
        if (interval > 1) {
            return "about " + Math.floor(interval) + " days ago.";
        }
        interval = seconds / 3600;
        if (interval > 1) {
            return "about " + Math.floor(interval) + " hours ago.";
        }
        interval = seconds / 60;
        if (interval > 1) {
            return "about " + Math.floor(interval) + " minutes ago.";
        }
        return "about " + Math.floor(seconds) + " seconds ago.";
    }

    static readingTimeWithCount(wordCount: number) {
        const wordsPerMinute = 250;
        const minutes = wordCount / wordsPerMinute;
        const time = Math.round(minutes * 60 * 1000);
        const displayed = Math.ceil(parseFloat(minutes.toFixed(2)));
        return displayed + " minutes read.";
    }

    static groupByKey(array: any[], key: any) {
        return array
            .reduce((hash, obj) => {
                if (obj[key] === undefined) return hash;
                return Object.assign(hash, { [obj[key]]: (hash[obj[key]] || []).concat(obj) })
            }, {})
    }

    static titleTransform(value: string): string {
        let first = value.substring(0, 1).toUpperCase();
        return first + value.substring(1);
    }

    static sleep(milliseconds: number) {
        let resolve: { (value: unknown): void; };
        let promise = new Promise((_resolve) => {
            resolve = _resolve;
        });
        setTimeout(() => resolve(1), milliseconds);
        return promise;
    }
}
