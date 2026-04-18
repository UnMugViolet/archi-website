pipeline {
    agent any
    
    environment {
        NODE_ENV = 'production'
        DOCKER_REGISTRY = 'unmugviolet'
    }
    tools {
        nodejs 'Main NodeJS'
    }
    stages {
        stage('SCM') {
            steps {
                git url: 'git@github.com:UnMugViolet/archi-website.git', branch: 'main'
            }
        }

        stage('SonarQube analysis') {
            steps {
                withSonarQubeEnv('Sonar-Server') {
                    sh "${tool 'SonarScanner'}/bin/sonar-scanner"
                }
            }
        }

        stage('Quality Gate') {
            steps {
                timeout(time: 1, unit: 'HOURS') {
                    waitForQualityGate abortPipeline: true
                }
            }
        }

        stage('Check Docker') {
            steps {
                sh 'docker --version'
                sh 'docker info'
            }
        }

        stage('Build Image') { 
            steps {
                script {
                    try {
                        sh 'ls -la ./Dockerfile || echo "Dockerfile not found"'

                        def imageName = "${DOCKER_REGISTRY}/archi-website:${env.BUILD_NUMBER}"
                        def latestImageName = "${DOCKER_REGISTRY}/archi-website:latest"
                        
                        sh """
                            DOCKER_BUILDKIT=0 docker build -t ${imageName} \
                            -t ${latestImageName} \
                            --build-arg NODE_ENV='${env.NODE_ENV}' \
                            --build-arg BUILD_NUMBER='${env.BUILD_NUMBER}' \
                            .
                        """

                        env.IMAGE_NAME = imageName
                        env.LATEST_IMAGE_NAME = latestImageName

                        echo "Docker image built successfully: ${env.IMAGE_NAME}"

                    } catch (Exception e) {
                        error "Failed to build Docker image: ${e.getMessage()}"
                    }
                }
            }
        }
        
        stage('Push Image') {
            steps {
                script {
                    docker.withRegistry('https://index.docker.io/v1/', 'dockerhub-credentials') {
                        def versionedImage = docker.image("${env.IMAGE_NAME}")
                        def latestImage = docker.image("${env.LATEST_IMAGE_NAME}")
                        
                        versionedImage.push()
                        latestImage.push()
                        
                        echo "Successfully pushed ${versionedImage} and ${latestImage} to Docker Hub"
                    }
                }
            }
        }
        
        stage('Deploy') {
            steps {
                sshPublisher(
                    publishers: [
                        sshPublisherDesc(
                            configName: 'Infomaniak',
                            transfers: [
                                sshTransfer(
                                    execCommand: '''
                                        cd ~/websites/archi-website &&
                                        echo "🛑 Stopping containers..." &&
                                        docker compose down &&
                                        echo "📥 Pulling latest images..." &&
                                        docker compose pull &&
                                        echo "🚀 Starting containers..." &&
                                        docker compose up -d &&
                                        echo "⏳ Waiting for containers to be ready..." &&
                                        sleep 10 &&
                                        echo "🔧 Running deployment tasks..." &&
                                        docker exec archi-website make deploy &&
                                        echo "🧹 Cleaning up..." &&
                                        docker system prune -f &&
                                        docker image prune -f &&
                                        echo "✅ Deployment completed successfully!"
                                    '''
                                )
                            ]
                        )
                    ]
                )
            }
        }
    }
}
