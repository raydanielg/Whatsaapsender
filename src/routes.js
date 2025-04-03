import { Router } from 'express'
import sessionsRoute from './wp/routes/sessionsRoute.js'
import messagesRoute from './wp/routes/messagesRoute.js'
import groupsRoute from './wp/routes/groupsRoute.js'
import response from './response.js'

const router = Router()

router.use('/sessions', sessionsRoute)
router.use('/message', messagesRoute)
router.use('/groups', groupsRoute)

router.all('*', (req, res) => {
    response(res, 404, false, 'Method not allowed')
})

export default router
