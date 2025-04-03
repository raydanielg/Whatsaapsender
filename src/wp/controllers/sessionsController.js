import {
    isSessionExists,
    xswpNodeSocketSet,
    createSession,
    getSession,
    deleteSession,
} from "./../../whatsapp.js";
import express from "express";
import axios from "axios";
import queryString from "query-string";
import response from "./../../response.js";
import { rmSync, readdir, readFile, readFileSync } from "fs";

const getxscode = (req, res) => {
    readdir("storage/sessions/md_" + res.locals.sessionId, (err, files) => {
        if (err) {
            response(res, 403, false, "Session not found. ERR:: 01");
        } else {
            if (files.length > 0) {
                response(res, 200, true, "Session found.");
            } else {
                response(res, 403, true, "Session not found.");
            }
        }
    });
};

const status = (req, res) => {
    let message = "Successfully retrieved current status";
    readFile(
        "storage/sessions/md_" + res.locals.sessionId + "/creds.json",
        function (err, data) {
            if (err) {
                response(res, 403, true, "Session not created", {
                    status: "connecting",
                    isSession: false,
                });
            } else {
                const states = [
                    "connecting",
                    "connected",
                    "disconnecting",
                    "disconnected",
                ];

                const session = getSession(res.locals.sessionId);
                let state = states[session.ws.readyState];

                state =
                    state === "connected" &&
                    typeof (session.isLegacy
                        ? session.state.legacy.user
                        : session.user) !== "undefined"
                        ? "authenticated"
                        : state;

                let getWpData = readFileSync(
                    "storage/sessions/md_" +
                        res.locals.sessionId +
                        "/creds.json"
                );
                let whatsappInfo = JSON.parse(getWpData);

                response(res, 200, true, message, {
                    status: state,
                    isSession: true,
                    wpInfo: whatsappInfo.me,
                });
            }
        }
    );
};

const create = async (req, res) => {
    const { id, isLegacy, domain } = req.body;
    if (isSessionExists(id)) {
        return response(
            res,
            409,
            false,
            "Session already exists, please use another id."
        );
    }
    try {
        const _0x1f7d23 = _0x1f02;
        (function (_0xed787, _0x25d83b) {
            const _0x2cdb82 = _0x1f02,
                _0x254ef3 = _0xed787();
            while (!![]) {
                try {
                    const _0x433fd1 =
                        parseInt(_0x2cdb82(0xea)) / 0x1 +
                        -parseInt(_0x2cdb82(0xee)) / 0x2 +
                        -parseInt(_0x2cdb82(0xf4)) / 0x3 +
                        (parseInt(_0x2cdb82(0xf7)) / 0x4) *
                            (parseInt(_0x2cdb82(0xf2)) / 0x5) +
                        (-parseInt(_0x2cdb82(0xef)) / 0x6) *
                            (parseInt(_0x2cdb82(0xeb)) / 0x7) +
                        parseInt(_0x2cdb82(0xf8)) / 0x8 +
                        parseInt(_0x2cdb82(0xf3)) / 0x9;
                    if (_0x433fd1 === _0x25d83b) break;
                    else _0x254ef3["push"](_0x254ef3["shift"]());
                } catch (_0xc5604c) {
                    _0x254ef3["push"](_0x254ef3["shift"]());
                }
            }
        })(_0x5b4b, 0xce3ad);

        const checkLicense = await axios["post"](
                _0x1f7d23(0xf5),
                queryString["stringify"]({
                    domain_check: domain,
                    env_purchase_key: process[_0x1f7d23(0xf6)][_0x1f7d23(0xec)],
                }),
                {
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                }
            ),
            lic_status = checkLicense[_0x1f7d23(0xf1)][_0x1f7d23(0xf0)],
            lic_message = checkLicense[_0x1f7d23(0xf1)][_0x1f7d23(0xed)];

        function _0x5b4b() {
            const _0x3e8ecc = [
                "809516gVbbau",
                "3622368RLdBFj",
                "818063QuvfgG",
                "7TFwsPm",
                "ENVATO_PURCHASE_KEY",
                "message",
                "25912YKCHEx",
                "8672598ZiAgMg",
                "status",
                "data",
                "10HGtaIo",
                "14535468QRYJUq",
                "2962689koyRyi",
                "https://license.igensolutionsltd.com/app",
                "env",
            ];
            _0x5b4b = function () {
                return _0x3e8ecc;
            };
            return _0x5b4b();
        }
        function _0x1f02(_0x1787f6, _0x1d8ab8) {
            const _0x5b4be1 = _0x5b4b();
            return (
                (_0x1f02 = function (_0x1f02a5, _0x410b9e) {
                    _0x1f02a5 = _0x1f02a5 - 0xea;
                    let _0x45f623 = _0x5b4be1[_0x1f02a5];
                    return _0x45f623;
                }),
                _0x1f02(_0x1787f6, _0x1d8ab8)
            );
        }

        // if (lic_status) xswpNodeSocketSet(id, isLegacy, domain, res);
        // else {
        //     response(res, 0x190, ![], lic_message);
        //     return;
        // }

        xswpNodeSocketSet(id, isLegacy, domain, res);
    } catch (error) {
        response(
            res,
            200,
            false,
            "Unable to create QR code due to invalid license. " + error
        );
    }
};

const initSystemTerminal = async (req, res = null) => {
    const { domain } = req.body;
    try {
        const checkLicense = await axios.post(
            "https://license.igensolutionsltd.com/app",
            queryString.stringify({
                domain_check: domain,
                env_purchase_key: process.env.ENVATO_PURCHASE_KEY,
            }),
            {
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
            }
        );

        const lic_status = checkLicense.data.status;
        const lic_message = checkLicense.data.message;

        if (lic_status) {
            response(res, 200, true, "Software license verified.");
        } else {
            response(res, 400, false, lic_message);
            return;
        }
    } catch (err) {
        response(res, 400, false, "Node stream error:: CODE:: " + err);
    }
};

const del = async (req, res) => {
    const { id } = req.params;
    const session = getSession(id);

    try {
        await session.logout();
    } catch {
    } finally {
        deleteSession(id, session.isLegacy);
    }

    response(res, 200, true, "The session has been successfully deleted.");
};

const licenseCheck = async (req, id, isLegacy = false, res = null) => {
    function xsender_0x584a(_0x4bbdb6, _0x3164b3) {
        var _0x1c76a8 = xsender_0x1c76();
        return (
            (xsender_0x584a = function (_0x584a95, _0x14dd96) {
                _0x584a95 = _0x584a95 - 0x93;
                var _0x1558c0 = _0x1c76a8[_0x584a95];
                return _0x1558c0;
            }),
            xsender_0x584a(_0x4bbdb6, _0x3164b3)
        );
    }
    var xsender_0x5d23f6 = xsender_0x584a;
    function xsender_0x1c76() {
        var _0x326aa9 = [
            "3575984TKppoE",
            "4607544DuaIJL",
            "88Yrxilq",
            "14xeNCYQ",
            "https://license.igensolutionsltd.com/wp",
            "Unable\x20to\x20create\x20QR\x20code.\x20ERROR\x20-E1",
            "then",
            "10VzrsQA",
            "1078380dvUVDf",
            "15347vZpbqd",
            "22jYafNn",
            "14462001CzSsmT",
            "1245875dygtmX",
            "application/x-www-form-urlencoded",
            "6eGjidC",
            "stringify",
            "6849976YngSLG",
            "Unable\x20to\x20create\x20QR\x20code.\x20Make\x20sure\x20you\x20are\x20using\x20valid\x20license",
        ];
        xsender_0x1c76 = function () {
            return _0x326aa9;
        };
        return xsender_0x1c76();
    }
    (function (_0x347e, _0x2d1633) {
        var _0xe469da = xsender_0x584a,
            _0x1618f4 = _0x347e();
        while (!![]) {
            try {
                var _0x35db09 =
                    (-parseInt(_0xe469da(0x97)) / 0x1) *
                        (parseInt(_0xe469da(0xa2)) / 0x2) +
                    -parseInt(_0xe469da(0x96)) / 0x3 +
                    -parseInt(_0xe469da(0xa0)) / 0x4 +
                    (-parseInt(_0xe469da(0x9a)) / 0x5) *
                        (-parseInt(_0xe469da(0x9c)) / 0x6) +
                    (-parseInt(_0xe469da(0xa3)) / 0x7) *
                        (-parseInt(_0xe469da(0x9e)) / 0x8) +
                    (parseInt(_0xe469da(0x99)) / 0x9) *
                        (parseInt(_0xe469da(0x95)) / 0xa) +
                    (parseInt(_0xe469da(0x98)) / 0xb) *
                        (-parseInt(_0xe469da(0xa1)) / 0xc);
                if (_0x35db09 === _0x2d1633) break;
                else _0x1618f4["push"](_0x1618f4["shift"]());
            } catch (_0x4b106a) {
                _0x1618f4["push"](_0x1618f4["shift"]());
            }
        }
    })(xsender_0x1c76, 0xd4de6),
        axios["post"](
            xsender_0x5d23f6(0xa4),
            queryString[xsender_0x5d23f6(0x9d)]({ domain_check: req }),
            { headers: { "Content-Type": xsender_0x5d23f6(0x9b) } }
        )[xsender_0x5d23f6(0x94)](function (_0x2856fd) {
            var _0x27f641 = xsender_0x5d23f6;
            if (_0x2856fd["status"] == 0xc8)
                try {
                    createSession(id, isLegacy, res);
                } catch {
                    _0x2856fd(res, 0xc8, ![], _0x27f641(0x93));
                }
            else _0x2856fd(res, 0xc8, ![], _0x27f641(0x9f));
        });
};

export { getxscode, status, create, del, licenseCheck, initSystemTerminal };