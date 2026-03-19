import { createServer } from "http";
import express from "express";
import { Server as SocketIOServer } from "socket.io";

const app = express();
const server = createServer(app);
const io = new SocketIOServer(server, {
	cors: {
		origin: ["http://localhost:3000"],
		methods: ["GET", "POST"],
	},
});

app.use(express.json());

// Health check endpoint
app.get("/health", (req, res) => {
	res.json({
		status: "ok",
		timestamp: new Date().toISOString(),
		connectedClients: io.engine.clientsCount,
		uptime: process.uptime()
	});
});

// Client connections
io.on("connection", (socket) => {
	console.log("Client connected:", socket.id);

	socket.on("join", (channel) => {
		socket.join(channel);
		console.log(`${socket.id} joined channel "${channel}"`);
		console.log(`Total clients in "${channel}":`, io.sockets.adapter.rooms.get(channel)?.size || 0);
	});

	socket.on("leave", (channel) => {
		socket.leave(channel);
		console.log(`${socket.id} left channel "${channel}"`);
	});

	socket.on("disconnect", () => {
		console.log("Client disconnected:", socket.id);
	});

	socket.on("error", (error) => {
		console.error("Socket error:", error);
	});
});

// Endpoint for Laravel to broadcast events
app.post("/broadcast", (req, res) => {
	let { channel, event, data } = req.body;

	console.log("Received broadcast:", { channel, event, data });

	if (!channel || !event) {
		return res.status(400).json({ error: "Missing channel or event" });
	}

	// Handle different channel types
	const originalChannel = channel;

	// Broadcast to the channel
	io.to(channel).emit(event, data);
	console.log(`Broadcasted "${event}" to channel "${channel}" (original: "${originalChannel}")`);
	console.log(`Connected clients in "${channel}":`, io.sockets.adapter.rooms.get(channel)?.size || 0);

	res.json({ success: true, message: `Broadcasted to ${channel}` });
});

const PORT = process.env.SOCKETIO_PORT || 3000;
server.listen(PORT, () => {
	console.log(`Socket.IO server running on port ${PORT}`);
});
